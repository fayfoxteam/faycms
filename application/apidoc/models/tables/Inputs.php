<?php
namespace apidoc\models\tables;

use fay\core\db\Table;

/**
 * Apidoc Inputs model
 * 
 * @property int $id Id
 * @property int $api_id 接口ID
 * @property string $name 名称
 * @property int $required 是否必须
 * @property int $type 参数类型
 * @property string $sample 示例值
 * @property string $description 描述
 * @property int $create_time 创建时间
 * @property int $last_modified_time 最后修改时间
 * @property string $since 自从
 */
class Inputs extends Table{
/**
	 * 类型 - 字符串
	 */
	const TYPE_STRING = 1;
	
	/**
	 * 类型 - 数字
	 */
	const TYPE_NUMBER = 2;
	
	protected $_name = 'apidoc_inputs';
	
	/**
	 * @return Inputs
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('api_id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('required', 'type'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('name'), 'string', array('max'=>255)),
			array(array('since'), 'string', array('max'=>30)),
			
			array('type', 'range', array('range'=>array(
				self::TYPE_STRING, self::TYPE_NUMBER
			))),
			array(array('name', 'required', 'type'), 'required')
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'api_id'=>'接口ID',
			'name'=>'名称',
			'required'=>'是否必须',
			'type'=>'参数类型',
			'sample'=>'示例值',
			'description'=>'描述',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后修改时间',
			'since'=>'自从',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'api_id'=>'intval',
			'name'=>'trim',
			'required'=>'intval',
			'type'=>'intval',
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
					'id', 'api_id', 'create_time'
				);
		}
	}
	
	/**
	 * 返回类型-类型描述数组
	 */
	public static function getTypes(){
		return array(
			self::TYPE_NUMBER => '数字',
			self::TYPE_STRING => '字符串',
		);
	}
}