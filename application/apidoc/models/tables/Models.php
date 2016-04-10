<?php
namespace apidoc\models\tables;

use fay\core\db\Table;

/**
 * Apidoc Models model
 * 
 * @property int $id Id
 * @property string $name 对象名称
 * @property string $sample 示例值
 * @property string $description 描述
 * @property string $since 自从
 * @property int $create_time 创建时间
 * @property int $last_modified_time 最后修改时间
 */
class Models extends Table{
	/**
	 * 特殊对象 - 字符串
	 */
	const ITEM_STRING = 1;
	
	/**
	 * 特殊对象 - 数字
	 */
	const ITEM_INT = 2;
	
	/**
	 * 特殊对象 - 字符串类型的数字
	 */
	const ITEM_NUMBER = 3;
	
	/**
	 * 特殊对象 - 布尔
	 */
	const ITEM_BOOLEAN = 4;
	
	/**
	 * 特殊对象 - 0，1标记
	 */
	const ITEM_BINARY = 5;
	
	/**
	 * 特殊对象 - 价格
	 */
	const ITEM_PRICE = 6;
	
	/**
	 * 特殊对象 - 数组
	 */
	const ITEM_ARRAY = 7;
	
	protected $_name = 'apidoc_models';
	
	/**
	 * @return Models
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('name'), 'string', array('max'=>50)),
			array(array('since'), 'string', array('max'=>30)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'name'=>'数据模型名称',
			'sample'=>'示例值',
			'description'=>'描述',
			'since'=>'自从',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后修改时间',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'name'=>'trim',
			'sample'=>'',
			'description'=>'',
			'since'=>'trim',
		);
	}
	
	public function getNotWritableFields($scene){
		switch($scene){
			case 'insert':
				return array('id');
			break;
			case 'update':
			default:
				return array(
					'id', 'create_time'
				);
		}
	}
	
	public function getPublicFields(){
		return $this->getFields(array('create_time', 'last_modified_time'));
	}
}