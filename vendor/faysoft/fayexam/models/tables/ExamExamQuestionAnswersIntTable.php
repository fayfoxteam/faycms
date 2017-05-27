<?php
namespace fayexam\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

class ExamExamQuestionAnswersIntTable extends Table{
    protected $_name = 'exam_exam_question_answers_int';
    protected $_primary = array('exam_question_id', 'user_answer_id');
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
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