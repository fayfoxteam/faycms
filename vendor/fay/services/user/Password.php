<?php
namespace fay\services\user;

use fay\core\Service;
use fay\helpers\StringHelper;

class Password extends Service{
	/**
	 * @param string $class_name
	 * @return Password
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 根据明码，得到一个加密后的密码和混淆码
	 * @param $password
	 * @return array
	 */
	public function generate($password){
		$salt = StringHelper::random('alnum', 5);
		return array(
			$salt,
			md5(md5($password) . $salt),
		);
	}
}