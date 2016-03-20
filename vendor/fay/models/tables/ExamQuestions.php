<?php
namespace fay\models\tables;

use fay\core\db\Table;

class ExamQuestions extends Table{
	/**
	 * 状态 - 激活
	 */
	const STATUS_ENABLED = 1;

	/**
	 * 状态 - 未激活
	 */
	const STATUS_DISABLED = 2;

	/**
	 * 类型 - 判断题
	 */
	const TYPE_TRUE_OR_FALSE = 1;

	/**
	 * 类型 - 单选
	 */
	const TYPE_SINGLE_ANSWER = 2;

	/**
	 * 类型 - 录入
	 */
	const TYPE_INPUT = 3;

	/**
	 * 类型 - 多选
	 */
	const TYPE_MULTIPLE_ANSWERS = 4;
	
	protected $_name = 'exam_questions';
	
	/**
	 * @return ExamQuestions
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('id', 'cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('type', 'status', 'rand'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('score'), 'float', array('length'=>5, 'decimal'=>2)),
			array(array('deleted'), 'range', array('range'=>array(0, 1))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'question'=>'试题',
			'cat_id'=>'分类',
			'score'=>'分值',
			'type'=>'类型',
			'sort'=>'排序',
			'status'=>'状态',
			'rand'=>'随机答案顺序',
			'create_time'=>'创建时间',
			'deleted'=>'删除',
		);
	}

	public function filters(){
		return array(
			'question'=>'',
			'cat_id'=>'intval',
			'score'=>'floatval',
			'type'=>'intval',
			'sort'=>'intval',
			'status'=>'intval',
			'rand'=>'intval',
			'create_time'=>'',
			'deleted'=>'intval',
		);
	}
}