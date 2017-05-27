<?php
namespace fayexam\models\tables;

use fay\core\db\Table;
use fay\core\Loader;

class ExamExamQuestionAnswerTextTable extends Table{
    protected $_name = 'exam_exam_question_answer_text';
    protected $_primary = 'exam_question_id';
    
    /**
     * @return $this
     */
    public static function model(){
        return Loader::singleton(__CLASS__);
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