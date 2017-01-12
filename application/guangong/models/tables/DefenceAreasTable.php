<?php
namespace guangong\models\tables;

use fay\core\db\Table;

/**
 * Guangong defence areas table model
 *
 * @property int $id 防区ID
 * @property string $name 防区名称
 */
class DefenceAreasTable extends Table{
	protected $_name = 'guangong_defence_areas';
	
	/**
	 * @param string $class_name
	 * @return DefenceAreasTable
	 
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
			'id'=>'防区ID',
			'name'=>'防区名称',
		);
	}
	
	public function filters(){
		return array(
			'id'=>'intval',
			'name'=>'trim',
		);
	}
}