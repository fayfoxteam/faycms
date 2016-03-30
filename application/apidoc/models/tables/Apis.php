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
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'标题',
			'router'=>'路由',
			'description'=>'描述',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'title'=>'trim',
			'router'=>'trim',
			'description'=>'',
		);
	}
}