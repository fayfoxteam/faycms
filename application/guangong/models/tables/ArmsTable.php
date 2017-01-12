<?php
namespace guangong\models\tables;

use fay\core\db\Table;

/**
 * Guangong arms table model
 *
 * @property int $id Id
 * @property string $name 名称
 * @property int $picture Picture
 */
class ArmsTable extends Table{
	protected $_name = 'guangong_arms';
	
	/**
	 * @param string $class_name
	 * @return ArmsTable
	 
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('picture'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('id'), 'int', array('min'=>0, 'max'=>255)),
			array(array('name'), 'string', array('max'=>30)),
		);
	}
	
	public function labels(){
		return array(
			'id'=>'Id',
			'name'=>'名称',
			'picture'=>'Picture',
		);
	}
	
	public function filters(){
		return array(
			'id'=>'intval',
			'name'=>'trim',
			'picture'=>'intval',
		);
	}
}