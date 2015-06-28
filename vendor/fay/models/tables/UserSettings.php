<?php
namespace fay\models\tables;

use fay\core\db\Table;

class UserSettings extends Table{
	protected $_name = 'user_settings';
	
	/**
	 * @return UserSettings
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('setting_key'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'user_id'=>'User Id',
			'setting_key'=>'Setting Key',
			'setting_value'=>'Setting Value',
		);
	}

	public function filters(){
		return array(
			'user_id'=>'intval',
			'setting_key'=>'trim',
			'setting_value'=>'',
		);
	}
}