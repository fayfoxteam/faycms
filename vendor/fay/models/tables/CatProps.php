<?php
namespace fay\models\tables;

use fay\core\db\Table;

class CatProps extends Table{
	/**
	 * 属性类型 - 多选
	 */
	const TYPE_CHECK = 1;

	/**
	 * 属性类型 - 单选
	 */
	const TYPE_OPTIONAL = 2;

	/**
	 * 属性类型 - 手工录入
	 */
	const TYPE_INPUT = 3;

	/**
	 * 属性类型 - 布尔属性
	 */
	const TYPE_BOOLEAN = 4;


	protected $_name = 'cat_props';
	
	/**
	 * @return CatProps
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('required'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('type', 'sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title'), 'string', array('max'=>255)),
			array(array('is_sale_prop', 'is_input_prop', 'deleted'), 'range', array('range'=>array('0', '1'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'type'=>'Type',
			'cat_id'=>'Cat Id',
			'required'=>'Required',
			'title'=>'Title',
			'is_sale_prop'=>'Is Sale Prop',
			'is_input_prop'=>'Is Input Prop',
			'deleted'=>'Deleted',
			'sort'=>'Sort',
		);
	}

	public function filters(){
		return array(
			'type'=>'intval',
			'cat_id'=>'intval',
			'required'=>'intval',
			'title'=>'trim',
			'is_sale_prop'=>'intval',
			'is_input_prop'=>'intval',
			'deleted'=>'intval',
			'sort'=>'intval',
		);
	}
}