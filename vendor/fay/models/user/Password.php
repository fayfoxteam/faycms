<?php
namespace fay\models\user;

use fay\core\Model;
use fay\helpers\StringHelper;

class Password extends Model{
	/**
	 * @param string $class_name
	 * @return Password
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 根据明码，得到一个加密后的密码和混淆码
	 * @param $password
	 * @return array
	 */
	public function generate($password){
		$salt = StringHelper::random('alnum', 5);
		return array(
			'salt'=>$salt,
			'password'=>md5(md5($password) . $salt),
		);
	}
}