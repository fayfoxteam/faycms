<?php
namespace apidoc\models\tables;

use fay\core\db\Table;

/**
 * Apidoc Outputs model
 * 
 * @property int $id Id
 * @property int $api_id API ID
 * @property int $model_id 数据模型ID
 * @property int $is_array 是否是数组
 * @property string $name 参数名称
 * @property string $sample 示例值
 * @property string $description 描述
 * @property int $sort 排序值
 * @property string $since 自从
 * @property int $create_time 创建时间
 * @property int $last_modified_time 最后修改时间
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
			array(array('id', 'model_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('api_id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('name'), 'string', array('max'=>50)),
			array(array('since'), 'string', array('max'=>30)),
			array(array('is_array'), 'range', array('range'=>array(0, 1))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'api_id'=>'API ID',
			'model_id'=>'数据模型ID',
			'is_array'=>'是否是数组',
			'name'=>'参数名称',
			'sample'=>'示例值',
			'description'=>'描述',
			'sort'=>'排序值',
			'since'=>'自从',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后修改时间',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'api_id'=>'intval',
			'model_id'=>'intval',
			'is_array'=>'intval',
			'name'=>'trim',
			'sample'=>'',
			'description'=>'',
			'sort'=>'intval',
			'since'=>'trim',
		);
	}
}