<?php
namespace apidoc\models\tables;

use fay\core\db\Table;

/**
 * Apidoc Outputs model
 * 
 * @property int $id Id
 * @property int $api_id 接口ID
 * @property string $title 标题
 * @property int $model_id 数据模型
 * @property string $sample 示例值
 * @property string $description 描述
 */
class Outputs extends Table{
	protected $_name = 'apidoc_outputs';
	
	/**
	 * @return Outputs
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('api_id', 'model_id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('title'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'api_id'=>'接口ID',
			'title'=>'标题',
			'model_id'=>'数据模型',
			'sample'=>'示例值',
			'description'=>'描述',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'api_id'=>'intval',
			'title'=>'trim',
			'model_id'=>'intval',
			'sample'=>'',
			'description'=>'',
		);
	}
}