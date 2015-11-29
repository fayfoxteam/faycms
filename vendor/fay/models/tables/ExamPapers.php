<?php
namespace fay\models\tables;

use fay\core\db\Table;

class ExamPapers extends Table{
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
	 * @return ExamPapers
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('create_time', 'last_modified_time'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('id', 'cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('rand', 'status', 'repeatedly'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('title'), 'string', array('max'=>255)),
			array(array('score'), 'float', array('length'=>5, 'decimal'=>2)),
			array(array('deleted'), 'range', array('range'=>array(0, 1))),
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
			'create_time'=>'Create Time',
			'last_modified_time'=>'Last Modified Time',
			'deleted'=>'删除',
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
			'create_time'=>'',
			'last_modified_time'=>'',
			'deleted'=>'intval',
		);
	}
}