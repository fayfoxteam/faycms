<?php
namespace guangong\models\tables;

use fay\core\db\Table;

/**
 * 结盟成员
 *
 * @property int $id Id
 * @property int $group_id 结盟ID
 * @property int $user_id 成员ID
 * @property int $accept 是否接受邀请
 * @property string $words 我想对兄弟说
 * @property int $secrecy_period 保密时间（单位：天）
 */
class GuangongUserGroupUsersTable extends Table{
	protected $_name = 'guangong_user_group_users';
	
	/**
	 * @param string $class_name
	 * @return GuangongUserGroupUsersTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('group_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('secrecy_period'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('accept'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('words'), 'string', array('max'=>255)),
		);
	}
	
	public function labels(){
		return array(
			'id'=>'Id',
			'group_id'=>'结盟ID',
			'user_id'=>'成员ID',
			'accept'=>'是否接受邀请',
			'words'=>'我想对兄弟说',
			'secrecy_period'=>'保密时间（单位：天）',
		);
	}
	
	public function filters(){
		return array(
			'id'=>'intval',
			'group_id'=>'intval',
			'user_id'=>'intval',
			'accept'=>'intval',
			'words'=>'trim',
			'secrecy_period'=>'intval',
		);
	}
}