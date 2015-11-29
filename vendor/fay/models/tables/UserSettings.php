<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * User Settings model
 * 
 * @property int $user_id
 * @property string $setting_key
 * @property string $setting_value
 */
class UserSettings extends Table{
	protected $_name = 'user_settings';
	protected $_primary = array('user_id', 'setting_key');
	
	/**
	 * @return UserSettings
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('setting_key'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
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