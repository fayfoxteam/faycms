<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Roles extends Table{
	protected $_name = 'roles';
	
	/**
	 * @return Roles
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('title', 'description'), 'string', array('max'=>255)),
			array(array('deleted', 'is_show'), 'range', array('range'=>array('0', '1'))),
			
			array(array('title'), 'unique', array('table'=>'roles', 'except'=>'id', 'ajax'=>array('admin/role/is-title-not-exist'))),
			array(array('title'), 'required'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'title'=>'角色名',
			'description'=>'描述',
			'deleted'=>'Deleted',
			'is_show'=>'Is Show',
		);
	}

	public function filters(){
		return array(
			'title'=>'trim',
			'description'=>'trim',
			'deleted'=>'intval',
			'is_show'=>'intval',
		);
	}
}