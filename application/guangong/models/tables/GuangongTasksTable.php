<?php
namespace guangong\models\tables;

use fay\core\db\Table;

/**
 * 任务
 * 
 * @property int $id Id
 * @property string $name 名称
 */
class GuangongTasksTable extends Table{
	protected $_name = 'guangong_tasks';
	
	/**
	 * @param string $class_name
	 * @return GuangongTasksTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('name'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'name'=>'名称',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'name'=>'trim',
		);
	}
}