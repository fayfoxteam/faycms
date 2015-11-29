<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * User Profile model
 * 
 * @property int $user_id
 * @property int $reg_time
 * @property int $reg_ip
 * @property int $login_times
 * @property int $last_login_time
 * @property int $last_login_ip
 * @property int $last_time_online
 * @property string $trackid
 * @property string $refer
 * @property string $se
 * @property string $keywords
 */
class UserProfile extends Table{
	protected $_name = 'user_profile';
	protected $_primary = 'user_id';
	
	/**
	 * @return UserProfile
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('last_login_ip'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('user_id', 'reg_ip'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('login_times'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('trackid'), 'string', array('max'=>50)),
			array(array('refer', 'keywords'), 'string', array('max'=>255)),
			array(array('se'), 'string', array('max'=>30)),
			array(array('reg_time', 'last_login_time', 'last_time_online'), 'datetime'),
		);
	}

	public function labels(){
		return array(
			'user_id'=>'用户ID',
			'reg_time'=>'注册时间',
			'reg_ip'=>'注册IP',
			'login_times'=>'登录次数',
			'last_login_time'=>'最后登录时间',
			'last_login_ip'=>'最后登录IP',
			'last_time_online'=>'最后在线时间',
			'trackid'=>'追踪ID',
			'refer'=>'来源URL',
			'se'=>'搜索引擎',
			'keywords'=>'搜索关键词',
		);
	}

	public function filters(){
		return array(
			'user_id'=>'intval',
			'reg_time'=>'trim',
			'reg_ip'=>'intval',
			'login_times'=>'trim',
			'last_login_time'=>'trim',
			'last_login_ip'=>'intval',
			'last_time_online'=>'trim',
			'trackid'=>'trim',
			'refer'=>'trim',
			'se'=>'trim',
			'keywords'=>'trim',
		);
	}
}