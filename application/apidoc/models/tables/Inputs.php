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
 * @property string $version 版本
 */
class Inputs extends Table{
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
			array(array('version'), 'string', array('max'=>30)),
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
			'version'=>'版本',
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
			'version'=>'trim',
		);
	}
}