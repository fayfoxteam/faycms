<?php
namespace steroid\models\forms;

use fay\core\Model;

class LeaveMessage extends Model{
	/**
	 * @param string $class_name
	 * @return LeaveMessage
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('name', 'email', 'phone', 'message'), 'required', array(
				'message'=>'{$attribute} can not be empty!'
			)),
			array('email', 'email', array(
				'message'=>'{$attribute} is not a valid email!'
			)),
		);
	}

	public function labels(){
		return array(
			'name'=>'Name',
			'email'=>'Email',
			'phone'=>'Phone',
			'message'=>'Message',
		);
	}

	public function filters(){
		return array(
			'name'=>'trim',
			'email'=>'trim',
			'phone'=>'trim',
			'message'=>'trim',
		);
	}
}