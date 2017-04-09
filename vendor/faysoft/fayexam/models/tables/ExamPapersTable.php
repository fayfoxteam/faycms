<?php
namespace fayexam\models\tables;

use fay\core\db\Table;

class ExamPapersTable extends Table{
    /**
     * 状态 - 激活
     */
    const STATUS_ENABLED = 1;
    
    /**
     * 状态 - 未激活
     */
    const STATUS_DISABLED = 2;
    
    protected $_name = 'exam_papers';
    
    /**
     * @param string $class_name
     * @return ExamPapersTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('create_time', 'update_time'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('id', 'cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('rand', 'status', 'repeatedly'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('title'), 'string', array('max'=>255)),
            array(array('score'), 'float', array('length'=>5, 'decimal'=>2)),
            array(array('delete_time'), 'range', array('range'=>array(0, 1))),
            array(array('start_time', 'end_time'), 'datetime'),
            
            array('title', 'required'),
        );
    }

    public function labels(){
        return array(
            'id'=>'Id',
            'title'=>'试卷名称',
            'description'=>'试卷描述',
            'cat_id'=>'分类ID',
            'rand'=>'随机题序',
            'status'=>'状态',
            'score'=>'试卷总分',
            'start_time'=>'考试开始时间',
            'end_time'=>'考试结束时间',
            'repeatedly'=>'重复参考',
            'create_time'=>'创建时间',
            'update_time'=>'最后更新时间',
            'delete_time'=>'删除时间',
        );
    }

    public function filters(){
        return array(
            'title'=>'trim',
            'description'=>'',
            'cat_id'=>'intval',
            'rand'=>'intval',
            'status'=>'intval',
            'score'=>'floatval',
            'start_time'=>'trim',
            'end_time'=>'trim',
            'repeatedly'=>'intval',
            'delete_time'=>'intval',
        );
    }
}