<?php
namespace fay\models\tables;

use fay\core\db\Table;

class UsersNotifications extends Table{
	protected $_name = 'users_notifications';
	protected $_primary = array('user_id', 'notification_id');
	
	/**
	 * @return UsersNotifications
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('user_id', 'notification_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('read', 'processed', 'ignored'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('option'), 'string', array('max'=>255)),
			array(array('deleted'), 'range', array('range'=>array('0', '1'))),
		);
	}

	public function labels(){
		return array(
			'user_id'=>'收件人',
			'notification_id'=>'消息ID',
			'read'=>'已读状态',
			'deleted'=>'删除状态',
			'processed'=>'是否处理',
			'ignored'=>'是否忽略',
			'option'=>'附加参数',
		);
	}

	public function filters(){
		return array(
			'user_id'=>'intval',
			'notification_id'=>'intval',
			'read'=>'intval',
			'deleted'=>'intval',
			'processed'=>'intval',
			'ignored'=>'intval',
			'option'=>'trim',
		);
	}
}