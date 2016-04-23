<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * User Logins model
 *
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property int $login_time 登录时间
 * @property int $ip_int IP
 * @property int $mac 唯一标识
 */
class UserLogins extends Table{
	protected $_name = 'user_logins';

	/**
	 * @return UserLogins
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}

	public function rules(){
		return array(
			array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('id', 'user_id', 'mac'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('login_time'), 'datetime'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'user_id'=>'用户ID',
			'login_time'=>'登录时间',
			'ip_int'=>'IP',
			'mac'=>'唯一标识',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'user_id'=>'intval',
			'login_time'=>'trim',
			'mac'=>'intval',
		);
	}
}