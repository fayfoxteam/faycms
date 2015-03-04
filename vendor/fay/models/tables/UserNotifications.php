<?php
namespace fay\models\tables;

use fay\core\db\Table;

class UserNotifications extends Table{
	protected $_name = 'user_notifications';
	
	/**
	 * @return UserNotifications
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'notification_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('to'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('read', 'processed', 'ignored'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('option'), 'string', array('max'=>255)),
			array(array('deleted'), 'range', array('range'=>array('0', '1'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'to'=>'To',
			'notification_id'=>'Notification Id',
			'read'=>'Read',
			'deleted'=>'Deleted',
			'processed'=>'Processed',
			'ignored'=>'Ignored',
			'option'=>'Option',
		);
	}

	public function filters(){
		return array(
			'to'=>'intval',
			'notification_id'=>'intval',
			'read'=>'intval',
			'deleted'=>'intval',
			'processed'=>'intval',
			'ignored'=>'intval',
			'option'=>'trim',
		);
	}
}