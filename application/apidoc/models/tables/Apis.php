<?php
namespace apidoc\models\tables;

use fay\core\db\Table;

/**
 * Apidoc Apis model
 * 
 * @property int $id Id
 * @property string $title 标题
 * @property string $router 路由
 * @property string $description 描述
 * @property int $create_time 创建时间
 * @property int $last_modified_time 最后修改时间
 * @property string $version 版本
 */
class Apis extends Table{
	protected $_name = 'apidoc_apis';
	
	/**
	 * @return Apis
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('title'), 'string', array('max'=>255)),
			array(array('router'), 'string', array('max'=>100)),
			array(array('version'), 'string', array('max'=>30)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'标题',
			'router'=>'路由',
			'description'=>'描述',
			'create_time'=>'创建时间',
			'last_modified_time'=>'最后修改时间',
			'version'=>'版本',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'title'=>'trim',
			'router'=>'trim',
			'description'=>'',
			'version'=>'trim',
		);
	}
}