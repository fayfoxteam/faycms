<?php
namespace apidoc\models\tables;

use fay\core\db\Table;

/**
 * Apidoc Models model
 * 
 * @property int $id Id
 * @property int $type 类型
 * @property int $parent 父节点
 */
class Models extends Table{
	/**
	 * 类型 - 字符串
	 */
	const TYPE_STRING = 1;
	
	/**
	 * 类型 - 数字
	 */
	const TYPE_INT = 2;
	
	/**
	 * 类型 - 字符串类型的数字
	 */
	const TYPE_NUMBER = 3;
	
	/**
	 * 类型 - 布尔
	 */
	const TYPE_BOOLEAN = 4;
	
	/**
	 * 类型 - 0，1标记
	 */
	const TYPE_BINARY = 5;
	
	/**
	 * 类型 - 价格
	 */
	const TYPE_PRICE = 6;
	
	/**
	 * 类型 - 数组
	 */
	const TYPE_ARRAY = 7;
	
	/**
	 * 类型 - 对象
	 */
	const TYPE_OBJECT = 8;
	
	
	protected $_name = 'apidoc_models';
	
	/**
	 * @return Models
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'parent'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('type'), 'int', array('min'=>-128, 'max'=>127)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'type'=>'类型',
			'parent'=>'父节点',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'type'=>'intval',
			'parent'=>'intval',
		);
	}
}