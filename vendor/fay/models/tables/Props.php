<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Props extends Table{
	/**
	 * 录入方式-文本框
	 */
	const ELEMENT_TEXT = 1;
	
	/**
	 * 录入方式-单选框
	 */
	const ELEMENT_RADIO = 2;
	
	/**
	 * 录入方式-下拉框
	 */
	const ELEMENT_SELECT = 3;
	
	/**
	 * 录入方式-多选框
	 */
	const ELEMENT_CHECKBOX = 4;
	
	/**
	 * 录入方式-文本域
	 */
	const ELEMENT_TEXTAREA = 5;
	
	/**
	 * 录入方式-文件
	 */
	const ELEMENT_FILE = 6;
	
	/**
	 * 录入方式-纯数字输入框
	 */
	const ELEMENT_NUMBER = 7;

	/**
	 * 类型-文章分类属性
	 */
	const TYPE_POST_CAT = 1;

	/**
	 * 类型-角色附加属性
	 */
	const TYPE_ROLE = 2;

	protected $_name = 'props';
	
	/**
	 * @param string $class_name
	 * @return Props
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('id', 'refer'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('type', 'element', 'sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('title'), 'string', array('max'=>255)),
			array(array('alias'), 'string', array('max'=>50, 'format'=>'alias')),
			array(array('deleted', 'is_show', 'required'), 'range', array('range'=>array(0, 1))),

			array('title', 'required'),
			array('alias', 'unique', array('table'=>'props', 'field'=>'alias', 'except'=>'id', 'ajax'=>array('admin/prop/is-alias-not-exist'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'refer'=>'Refer',
			'type'=>'Type',
			'title'=>'属性名称',
			'element'=>'Element',
			'required'=>'必选标记',
			'alias'=>'别名',
			'deleted'=>'删除标记',
			'sort'=>'排序值',
			'create_time'=>'创建时间',
			'is_show'=>'Is Show',
		);
	}

	public function filters(){
		return array(
			'refer'=>'intval',
			'type'=>'intval',
			'title'=>'trim',
			'element'=>'intval',
			'required'=>'intval',
			'alias'=>'trim',
			'deleted'=>'intval',
			'sort'=>'intval',
			'is_show'=>'intval',
		);
	}
}