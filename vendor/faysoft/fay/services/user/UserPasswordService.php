<?php
namespace fay\services\user;
	
	use fay\core\Service;
	use fay\helpers\StringHelper;
	use fay\models\tables\Users;

class UserPasswordService extends Service{
	/**
	 * @param string $class_name
	 * @return UserPasswordService
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
	
	/**
	 * 验证指定的用户名密码是否匹配
	 * @param string $username
	 * @param string $password
	 * @param bool $admin 若为true，则限定为管理员登录（管理员也可以登录前台，但前后台的Session空间是分开的）
	 * @return array
	 */
	public function checkPassword($username, $password, $admin = false){
		if(!$username){
			return array(
				'status'=>0,
				'message'=>'用户名不能为空',
				'error_code'=>'username:can-not-be-empty',
			);
		}
		if(!$password){
			return array(
				'status'=>0,
				'message'=>'密码不能为空',
				'error_code'=>'password:can-not-be-empty',
			);
		}
		
		$user = Users::model()->fetchRow(array(
			'username = ?'=>$username,
			'deleted = 0',
		), 'id,password,salt,block,status,admin');
		
		//判断用户名是否存在
		if(!$user){
			return array(
				'user_id'=>0,
				'message'=>'用户名不存在',
				'error_code'=>'username:not-exist',
			);
		}
		$password = md5(md5($password).$user['salt']);
		if($password != $user['password']){
			return array(
				'user_id'=>0,
				'message'=>'密码错误',
				'error_code'=>'password:not-match',
			);
		}
		
		if($user['block']){
			return array(
				'user_id'=>0,
				'message'=>'用户已锁定',
				'error_code'=>'block:blocked',
			);
		}
		
		if($user['status'] == Users::STATUS_UNCOMPLETED){
			return array(
				'user_id'=>0,
				'message'=>'账号信息不完整',
				'error_code'=>'status:uncompleted',
			);
		}else if($user['status'] == Users::STATUS_PENDING){
			return array(
				'user_id'=>0,
				'message'=>'账号正在审核中',
				'error_code'=>'status:pending',
			);
		}else if($user['status'] == Users::STATUS_VERIFY_FAILED){
			return array(
				'user_id'=>0,
				'message'=>'账号未通过审核',
				'error_code'=>'status:verify-failed',
			);
		}else if($user['status'] == Users::STATUS_NOT_VERIFIED){
			return array(
				'user_id'=>0,
				'message'=>'请先验证邮箱',
				'error_code'=>'status:not-verified',
			);
		}
		
		if($admin && $user['admin'] != $admin){
			return array(
				'user_id'=>0,
				'message'=>'您不是管理员，不能登陆！',
				'error_code'=>'not-admin',
			);
		}
		
		return array(
			'user_id'=>$user['id'],
			'message'=>'',
			'error_code'=>'',
		);
	}
}