<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\User;

/**
 * 登录
 */
class LoginController extends ApiController{
	/**
	 * 登录
	 * @parameter string $username 用户名
	 * @parameter string $password 密码
	 */
	public function index(){
		if($this->input->post()){
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$result = User::model()->login($username, $password);
			if($result['status']){
				Response::notify('success', array(
					'message'=>'登录成功',
					'data'=>array(
						'user'=>array(
							'id'=>$result['user']['user']['id'],
							'username'=>$result['user']['user']['username'],
							'nickname'=>$result['user']['user']['nickname'],
							'avatar'=>$result['user']['user']['avatar'],
						),
						\F::config()->get('session.ini_set.name')=>session_id(),
					),
				));
			}else{
				Response::notify('error', array(
					'message'=>isset($result['message']) ? $result['message'] : '登录失败',
					'code'=>isset($result['error_code']) ? $result['error_code'] : '',
				));
			}
		}else{
			Response::notify('error', array(
				'message'=>'登录失败',
				'code'=>'no-post-data',
			));
		}
	}
	
	/**
	 * 登出
	 */
	public function logout(){
		User::model()->logout();
		
		Response::notify('success', array(
			'message'=>'退出登录',
		));
	}
}