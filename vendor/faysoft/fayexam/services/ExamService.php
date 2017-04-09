<?php
namespace fayexam\services;

use fay\core\Service;
use fayexam\models\tables\ExamPapersTable;
use fay\core\Sql;
use fayexam\models\tables\ExamExamQuestionAnswersIntTable;
use fayexam\models\tables\ExamAnswersTable;
use fayexam\models\tables\ExamQuestionsTable;
use fayexam\models\tables\ExamPaperQuestionsTable;
use fayexam\models\tables\ExamExamsTable;
use fayexam\models\tables\ExamExamsQuestionsTable;
use fayexam\models\tables\ExamExamQuestionAnswerTextTable;
use fay\helpers\StringHelper;

class ExamService extends Service{
    /**
     * @param string $class_name
     * @return ExamService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    
    public function getPaper($id){
        $paper = ExamPapersTable::model()->find($id);
        
        $sql = new Sql();
        $paper['questions'] = $sql->from(array('pq'=>'exam_paper_questions'), 'score')
            ->joinLeft(array('q'=>'exam_questions'), 'pq.question_id = q.id', 'id,question,type,rand')
            ->where('pq.paper_id = '.$paper['id'])
            ->order('pq.sort')
            ->fetchAll()
        ;
        
        foreach($paper['questions'] as &$q){
            if(in_array($q['type'], array(
                ExamQuestionsTable::TYPE_TRUE_OR_FALSE,
                ExamQuestionsTable::TYPE_SINGLE_ANSWER,
                ExamQuestionsTable::TYPE_MULTIPLE_ANSWERS,
            ))){
                $q['answers'] = ExamAnswersTable::model()->fetchAll('question_id = '.$q['id'], 'id,answer', 'sort');
                if($q['rand']){
                    shuffle($q['answers']);
                }
            }
        }
        
        if($paper['rand']){
            shuffle($paper['questions']);
        }
        
        return $paper;
    }
    
    /**
     * 判断一个选择题的答案是否参与考试并且已被用户选中
     * @param $answer_id
     * @return bool
     */
    public static function isAnswerExamed($answer_id){
        return !!ExamExamQuestionAnswersIntTable::model()->fetchRow(array(
            'user_answer_id = ?'=>$answer_id,
        ));
    }
    
    /**
     * 记录一份大卷
     * @param $paper
     * @param int $start_time
     * @param int $user_answers 用户作答，键值对形式
     * @param int $user_id
     * @return int
     */
    public function record($paper, $start_time, $user_answers, $user_id = null){
        $user_id || $user_id = \F::app()->current_user;
        
        StringHelper::isInt($paper) && $paper = ExamPapersTable::model()->find($paper, 'id,rand');
        
        $exam_id = ExamExamsTable::model()->insert(array(
            'user_id'=>$user_id,
            'paper_id'=>$paper['id'],
            'start_time'=>$start_time,
            'end_time'=>\F::app()->current_time,
            'rand'=>$paper['rand'],
        ));
        
        $questions = ExamPaperQuestionsTable::model()->fetchAll(array(
            'paper_id = ?'=>$paper['id'],
        ), 'question_id,score', 'sort');
        
        $total_score = 0;
        $user_score = 0;
        foreach($questions as $q){
            $total_score += $q['score'];
            
            $question = ExamQuestionsTable::model()->find($q['question_id'], 'type');
            switch($question['type']){
                case ExamQuestionsTable::TYPE_TRUE_OR_FALSE:
                case ExamQuestionsTable::TYPE_SINGLE_ANSWER:
                    //判断题，除非选中正确答案，否则都视为错误答案
                    $answer = ExamAnswersTable::model()->fetchRow(array(
                        'question_id = '.$q['question_id'],
                        'is_right_answer = 1',
                    ), 'id', 'sort');
                    if(isset($user_answers[$q['question_id']]) && $answer['id'] == $user_answers[$q['question_id']]){
                        //回答正确
                        $score = $q['score'];
                        $user_score += $q['score'];
                    }else{
                        //回答错误
                        $score = 0;
                    }
                    $exam_question_id = ExamExamsQuestionsTable::model()->insert(array(
                        'exam_id'=>$exam_id,
                        'question_id'=>$q['question_id'],
                        'total_score'=>$q['score'],
                        'score'=>$score,
                    ));
                    if(isset($user_answers[$q['question_id']])){
                        ExamExamQuestionAnswersIntTable::model()->insert(array(
                            'exam_question_id'=>$exam_question_id,
                            'user_answer_id'=>$user_answers[$q['question_id']],
                        ));
                    }
                break;
                case ExamQuestionsTable::TYPE_INPUT:
                    //填空题默认为0分，由其他逻辑确定得分
                    $exam_question_id = ExamExamsQuestionsTable::model()->insert(array(
                        'exam_id'=>$exam_id,
                        'question_id'=>$q['question_id'],
                        'total_score'=>$q['score'],
                        'score'=>0,
                    ));
                    ExamExamQuestionAnswerTextTable::model()->insert(array(
                        'exam_question_id'=>$exam_question_id,
                        'user_answer'=>isset($user_answers[$q['question_id']]) ? $user_answers[$q['question_id']] : '',
                    ));
                break;
                case ExamQuestionsTable::TYPE_MULTIPLE_ANSWERS:
                    isset($user_answers[$q['question_id']]) || $user_answers[$q['question_id']] = array();
                    $answers = ExamAnswersTable::model()->fetchCol('id', array(
                        'question_id = '.$q['question_id'],
                        'is_right_answer = 1',
                    ));
                    
                    //全部正确才得分
                    if(array_diff($answers, $user_answers[$q['question_id']]) ||
                        array_diff($user_answers[$q['question_id']], $answers)){
                        $score = 0;
                    }else{
                        $score = $q['score'];
                        $user_score += $q['score'];
                    }
                    $exam_question_id = ExamExamsQuestionsTable::model()->insert(array(
                        'exam_id'=>$exam_id,
                        'question_id'=>$q['question_id'],
                        'total_score'=>$q['score'],
                        'score'=>$score,
                    ));
                    foreach($user_answers[$q['question_id']] as $ua){
                        ExamExamQuestionAnswersIntTable::model()->insert(array(
                            'exam_question_id'=>$exam_question_id,
                            'user_answer_id'=>$ua,
                        ));
                    }
                break;
            }
        }
        
        //更新总分
        ExamExamsTable::model()->update(array(
            'score'=>$user_score,
            'total_score'=>$total_score,
        ), $exam_id);
        
        return $exam_id;
    }
    
    public function remove($exem_id){
        $exam_question_ids = ExamExamsQuestionsTable::model()->fetchCol('id', 'exam_id = '.$exem_id);
        
        //删除答案
        ExamExamQuestionAnswersIntTable::model()->delete(array(
            'exam_question_id IN (?)'=>$exam_question_ids,
        ));
        ExamExamQuestionAnswerTextTable::model()->delete(array(
            'exam_question_id IN (?)'=>$exam_question_ids,
        ));
        
        //删除答案得分
        ExamExamsQuestionsTable::model()->delete('exam_id = '.$exem_id);
        
        //删除考卷信息
        ExamExamsTable::model()->delete('id = '.$exem_id);
        
        return true;
    }
}