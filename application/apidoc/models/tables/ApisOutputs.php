<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Api Apis Outputs model
 * 
 * @property int $api_id API ID
 * @property int $output_id 输出参数ID
 * @property int $sort 排序值
 * @property int $create_time 创建时间
 * @property int $last_modified_time 最后修改时间
 * @property string $since 自从
 */
class ApiApisOutputs extends Table{
	protected $_name = 'api_apis_outputs';
	protected $_primary = array('api_id', 'output_id');
	
	/**
	 * @return ApiApisOutputs
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('output_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('api_id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('since'), 'string', array('max'=>30)),
		);
	}

	public function labels(){
		return array(
			'api_id'=>'API ID',
			'output_id'=>'输出参数ID',
			'sort'=>'排序值',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后修改时间',
			'since'=>'自从',
		);
	}

	public function filters(){
		return array(
			'api_id'=>'intval',
			'output_id'=>'intval',
			'sort'=>'intval',
			'since'=>'trim',
		);
	}
}