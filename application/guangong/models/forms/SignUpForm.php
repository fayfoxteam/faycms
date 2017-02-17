<?php
namespace guangong\models\forms;

use fay\core\Model;

class SignUpForm extends Model{
	/**
	 * @param string $class_name
	 * @return SignUpForm
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('mobile', 'birthday', 'state', 'city', 'district'), 'required'),
			array('mobile', 'mobile'),
			//array('captcha', 'captcha'),
		);
	}
	
	public function labels(){
		return array(
			'mobile'=>'识别码',
			'birthday'=>'出生期',
			'state'=>'省',
			'city'=>'市',
			'district'=>'县',
			//'captcha'=>'验证码',
		);
	}
	
	public function filters(){
		return array(
			'name'=>'trim',
			'email'=>'trim',
			'subject'=>'trim',
			'message'=>'trim',
		);
	}
}