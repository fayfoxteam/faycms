<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Cities extends Table{
	protected $_name = 'cities';
	
	/**
	 * @param string $class_name
	 * @return Cities
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('parent'), 'int', array('min'=>-32768, 'max'=>32767)),
			array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('city'), 'string', array('max'=>255)),
			array(array('spelling'), 'string', array('max'=>50)),
			array(array('abbr', 'short'), 'string', array('max'=>30)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'city'=>'City',
			'parent'=>'Parent',
			'spelling'=>'Spelling',
			'abbr'=>'缩写',
			'short'=>'单个首字母',
		);
	}

	public function filters(){
		return array(
			'city'=>'trim',
			'parent'=>'intval',
			'spelling'=>'trim',
			'abbr'=>'trim',
			'short'=>'trim',
		);
	}
}