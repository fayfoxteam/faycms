<?php
namespace apidoc\models\tables;

use fay\core\db\Table;

/**
 * Apidoc Model Props model
 * 
 * @property int $id Id
 * @property int $model_id 数据模型ID
 * @property string $name 属性名称
 * @property int $type 类型
 * @property string $sample 示例值
 * @property string $description 描述
 * @property string $since 自从
 * @property int $create_time 创建时间
 * @property int $last_modified_time 最后修改时间
 */
class ModelProps extends Table{
	protected $_name = 'apidoc_model_props';
	
	/**
	 * @return ModelProps
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('model_id', 'type'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('name'), 'string', array('max'=>50)),
			array(array('since'), 'string', array('max'=>30)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'model_id'=>'数据模型ID',
			'name'=>'属性名称',
			'type'=>'类型',
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
			'model_id'=>'intval',
			'name'=>'trim',
			'type'=>'intval',
			'sample'=>'',
			'description'=>'',
			'since'=>'trim',
		);
	}
	
	public function getPublicFields(){
		return $this->getFields(array('create_time', 'last_modified_time'));
	}
}