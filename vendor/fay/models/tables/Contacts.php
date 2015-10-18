<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Contacts extends Table{
	/**
	 * 状态 - 未读
	 */
	const STATUS_UNREAD = 1;
	
	/**
	 * 状态 - 已读
	 */
	const STATUS_READ = 2;

	protected $_name = 'contacts';
	
	/**
	 * @return Contacts
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('id', 'create_time', 'parent'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('status'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('realname', 'phone'), 'string', array('max'=>50)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'realname'=>'Realname',
			'email'=>'Email',
			'phone'=>'Phone',
			'content'=>'Content',
			'create_time'=>'Create Time',
			'ip_int'=>'Ip Int',
			'parent'=>'Parent',
			'status'=>'Status',
		);
	}

	public function filters(){
		return array(
			'realname'=>'trim',
			'email'=>'trim',
			'phone'=>'trim',
			'content'=>'',
			'create_time'=>'',
			'ip_int'=>'intval',
			'parent'=>'intval',
			'status'=>'intval',
		);
	}
}