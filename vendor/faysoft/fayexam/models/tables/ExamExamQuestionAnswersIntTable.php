<?php
namespace fayexam\models\tables;

use fay\core\db\Table;

class ExamExamQuestionAnswersIntTable extends Table{
    protected $_name = 'exam_exam_question_answers_int';
    protected $_primary = array('exam_question_id', 'user_answer_id');
    
    /**
     * @param string $class_name
     * @return ExamExamQuestionAnswersIntTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('exam_question_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('user_answer_id'), 'int', array('min'=>0, 'max'=>16777215)),
        );
    }

    public function labels(){
        return array(
            'exam_question_id'=>'Exam Question Id',
            'user_answer_id'=>'User Answer Id',
        );
    }

    public function filters(){
        return array(
            'exam_question_id'=>'intval',
            'user_answer_id'=>'intval',
        );
    }
}