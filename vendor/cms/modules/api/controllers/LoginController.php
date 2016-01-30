<?php
namespace cms\modules\api\controllers;

use cms\library\ApiController;
use fay\models\User;
use fay\core\Response;
use fay\models\File;

class LoginController extends ApiController{
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
							'username'=>$result['user']['user']['username'],
							'nickname'=>$result['user']['user']['nickname'],
							'avatar'=>$result['user']['user']['avatar'],
							'avatar_url'=>$result['user']['user']['avatar_url'],
						),
					),
				));
			}else{
				Response::notify('error', array(
					'message'=>isset($result['message']) ? $result['message'] : '登录失败',
					'code'=>isset($result['error_code']) ? $result['error_code'] : '',
				));
			}
		}
	}
	
	public function logout(){
		User::model()->logout();
		
		Response::notify('success', array(
			'message'=>'退出登录',
		));
	}
}