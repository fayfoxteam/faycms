<?php
namespace apidoc\models\tables;

use fay\core\db\Table;

/**
 * Apidoc Inputs model
 * 
 * @property int $id Id
 * @property int $api_id 接口ID
 * @property string $title 标题
 * @property int $required 是否必须
 * @property int $type 参数类型
 * @property string $sample 示例值
 * @property string $description 描述
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
			array(array('title'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'api_id'=>'接口ID',
			'title'=>'标题',
			'required'=>'是否必须',
			'type'=>'参数类型',
			'sample'=>'示例值',
			'description'=>'描述',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'api_id'=>'intval',
			'title'=>'trim',
			'required'=>'intval',
			'type'=>'intval',
			'sample'=>'',
			'description'=>'',
		);
	}
}