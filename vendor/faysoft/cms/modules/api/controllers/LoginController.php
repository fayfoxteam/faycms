<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\UserService;
use fay\services\user\UserPasswordService;

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
			$result = UserPasswordService::service()->checkPassword(
				$this->input->post('username'),
				$this->input->post('password')
			);
			
			if($result['user_id']){
				$user = UserService::service()->login($result['user_id']);
			}else{
				Response::notify('error', array(
					'message'=>isset($result['message']) ? $result['message'] : '登录失败',
					'code'=>isset($result['error_code']) ? $result['error_code'] : '',
				));
			}
			
			if($user){
				Response::notify('success', array(
					'message'=>'登录成功',
					'data'=>array(
						'user'=>array(
							'id'=>$user['user']['user']['id'],
							'username'=>$user['user']['user']['username'],
							'nickname'=>$user['user']['user']['nickname'],
							'avatar'=>$user['user']['user']['avatar'],
						),
						\F::config()->get('session.ini_set.name')=>session_id(),
					),
				));
			}else{
				Response::notify('error', array(
					'message'=>'登录失败',
					'code'=>'no-post-data',
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
		UserService::service()->logout();
		
		Response::notify('success', array(
			'message'=>'退出登录',
		));
	}
}