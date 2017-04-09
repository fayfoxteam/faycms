<?php
namespace fayexam\models\tables;

use fay\core\db\Table;

class ExamExamQuestionAnswerTextTable extends Table{
    protected $_name = 'exam_exam_question_answer_text';
    protected $_primary = 'exam_question_id';
    
    /**
     * @param string $class_name
     * @return ExamExamQuestionAnswerTextTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('exam_question_id'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
        );
    }

    public function labels(){
        return array(
            'exam_question_id'=>'Exam Question Id',
            'user_answer'=>'User Answer',
        );
    }

    public function filters(){
        return array(
            'exam_question_id'=>'intval',
            'user_answer'=>'',
        );
    }
}