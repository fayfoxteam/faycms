<?php
namespace apidoc\models\tables;

use fay\core\db\Table;

/**
 * Apidoc Outputs model
 * 
 * @property int $id Id
 * @property string $name 名称
 * @property int $type 类型
 * @property string $sample 示例值
 * @property string $description 描述
 * @property int $parent 父节点
 * @property int $create_time 创建时间
 * @property int $last_modified_time 最后修改时间
 * @property string $since 自从
 */
class Outputs extends Table{
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
	
	protected $_name = 'apidoc_outputs';
	
	/**
	 * @return Outputs
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'parent'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('type'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('name'), 'string', array('max'=>255)),
			array(array('since'), 'string', array('max'=>30)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'name'=>'名称',
			'type'=>'类型',
			'sample'=>'示例值',
			'description'=>'描述',
			'parent'=>'父节点',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后修改时间',
			'since'=>'自从',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'name'=>'trim',
			'type'=>'intval',
			'sample'=>'',
			'description'=>'',
			'parent'=>'intval',
			'since'=>'trim',
		);
	}
}