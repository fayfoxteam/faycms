<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\ExamPapers;
use fay\core\Sql;
use fay\models\tables\ExamExamQuestionAnswersInt;
use fay\models\tables\ExamAnswers;
use fay\models\tables\ExamQuestions;
use fay\models\tables\ExamPaperQuestions;
use fay\models\tables\ExamExams;
use fay\models\tables\ExamExamsQuestions;
use fay\models\tables\ExamExamQuestionAnswerText;
use fay\helpers\StringHelper;

class Exam extends Model{
	/**
	 * @return Exam
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	
	public function getPaper($id){
		$paper = ExamPapers::model()->find($id);
		
		$sql = new Sql();
		$paper['questions'] = $sql->from(array('pq'=>'exam_paper_questions'), 'score')
			->joinLeft(array('q'=>'exam_questions'), 'pq.question_id = q.id', 'id,question,type,rand')
			->where('pq.paper_id = '.$paper['id'])
			->order('pq.sort')
			->fetchAll()
		;
		
		foreach($paper['questions'] as &$q){
			if(in_array($q['type'], array(
				ExamQuestions::TYPE_TRUE_OR_FALSE,
				ExamQuestions::TYPE_SINGLE_ANSWER,
				ExamQuestions::TYPE_MULTIPLE_ANSWERS,
			))){
				$q['answers'] = ExamAnswers::model()->fetchAll('question_id = '.$q['id'], 'id,answer', 'sort');
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
		return !!ExamExamQuestionAnswersInt::model()->fetchRow(array(
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
	 * @internal param int $paper_id
	 */
	public function record($paper, $start_time, $user_answers, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		
		StringHelper::isInt($paper) && $paper = ExamPapers::model()->find($paper, 'id,rand');
		
		$exam_id = ExamExams::model()->insert(array(
			'user_id'=>$user_id,
			'paper_id'=>$paper['id'],
			'start_time'=>$start_time,
			'end_time'=>\F::app()->current_time,
			'rand'=>$paper['rand'],
		));
		
		$questions = ExamPaperQuestions::model()->fetchAll(array(
			'paper_id = ?'=>$paper['id'],
		), 'question_id,score', 'sort');
		
		$total_score = 0;
		$user_score = 0;
		foreach($questions as $q){
			$total_score += $q['score'];
			
			$question = ExamQuestions::model()->find($q['question_id'], 'type');
			switch($question['type']){
				case ExamQuestions::TYPE_TRUE_OR_FALSE:
				case ExamQuestions::TYPE_SINGLE_ANSWER:
					//判断题，除非选中正确答案，否则都视为错误答案
					$answer = ExamAnswers::model()->fetchRow(array(
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
					$exam_question_id = ExamExamsQuestions::model()->insert(array(
						'exam_id'=>$exam_id,
						'question_id'=>$q['question_id'],
						'total_score'=>$q['score'],
						'score'=>$score,
					));
					if(isset($user_answers[$q['question_id']])){
						ExamExamQuestionAnswersInt::model()->insert(array(
							'exam_question_id'=>$exam_question_id,
							'user_answer_id'=>$user_answers[$q['question_id']],
						));
					}
				break;
				case ExamQuestions::TYPE_INPUT:
					//填空题默认为0分，由其他逻辑确定得分
					$exam_question_id = ExamExamsQuestions::model()->insert(array(
						'exam_id'=>$exam_id,
						'question_id'=>$q['question_id'],
						'total_score'=>$q['score'],
						'score'=>0,
					));
					ExamExamQuestionAnswerText::model()->insert(array(
						'exam_question_id'=>$exam_question_id,
						'user_answer'=>isset($user_answers[$q['question_id']]) ? $user_answers[$q['question_id']] : '',
					));
				break;
				case ExamQuestions::TYPE_MULTIPLE_ANSWERS:
					isset($user_answers[$q['question_id']]) || $user_answers[$q['question_id']] = array();
					$answers = ExamAnswers::model()->fetchCol('id', array(
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
					$exam_question_id = ExamExamsQuestions::model()->insert(array(
						'exam_id'=>$exam_id,
						'question_id'=>$q['question_id'],
						'total_score'=>$q['score'],
						'score'=>$score,
					));
					foreach($user_answers[$q['question_id']] as $ua){
						ExamExamQuestionAnswersInt::model()->insert(array(
							'exam_question_id'=>$exam_question_id,
							'user_answer_id'=>$ua,
						));
					}
				break;
			}
		}
		
		//更新总分
		ExamExams::model()->update(array(
			'score'=>$user_score,
			'total_score'=>$total_score,
		), $exam_id);
		
		return $exam_id;
	}
	
	public function remove($exem_id){
		$exam_question_ids = ExamExamsQuestions::model()->fetchCol('id', 'exam_id = '.$exem_id);
		
		//删除答案
		ExamExamQuestionAnswersInt::model()->delete(array(
			'exam_question_id IN (?)'=>$exam_question_ids,
		));
		ExamExamQuestionAnswerText::model()->delete(array(
			'exam_question_id IN (?)'=>$exam_question_ids,
		));
		
		//删除答案得分
		ExamExamsQuestions::model()->delete('exam_id = '.$exem_id);
		
		//删除考卷信息
		ExamExams::model()->delete('id = '.$exem_id);
		
		return true;
	}
}