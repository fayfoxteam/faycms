<?php
namespace fay\models\tables;

use fay\core\db\Table;

class RolesCats extends Table{
	protected $_name = 'roles_cats';
	protected $_primary = array('role_id', 'cat_id');
	
	/**
	 * @return RolesCats
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('role_id', 'cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
		);
	}

	public function labels(){
		return array(
			'role_id'=>'Role Id',
			'cat_id'=>'Cat Id',
		);
	}

	public function filters(){
		return array(
			'role_id'=>'intval',
			'cat_id'=>'intval',
		);
	}
}