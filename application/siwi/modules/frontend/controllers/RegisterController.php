<?php
namespace siwi\modules\frontend\controllers;

use siwi\library\FrontController;
use fay\helpers\StringHelper;
use fay\models\tables\Users;
use fay\helpers\RequestHelper;
use fay\core\HttpException;
use fay\core\Validator;

class RegisterController extends FrontController{
	public function mini(){
		if($this->input->post()){
			$validator = new Validator();
			$check = $validator->check(array(
				array(array('username', 'password'), 'require'),
				array(array('username'), 'string', array('format'=>'alias', 'max'=>20, 'min'=>2)),
				array(array('username'), 'unique', array('table'=>'users')),
			), array(
				'username'=>'用户名',
				'password'=>'密码',
			));
			
			if($check === true){
				$salt = StringHelper::random('alnum', 5);
				$username = $this->input->post('username');
				$password = md5(md5($this->input->post('password')).$salt);
				
				$data = array(
					'username'=>$username,
					'nickname'=>$username,
					'salt'=>$salt,
					'status'=>Users::STATUS_VERIFIED,
					'password'=>$password,
					'role'=>Users::ROLE_USER,
					'reg_time'=>$this->current_time,
					'reg_ip'=>RequestHelper::ip2int($this->ip),
				);
				
				$user_id = Users::model()->insert($data);
				
				//UserService::service()->login($username, $this->input->post('password'));
			}else{
				throw new HttpException('参数异常', 500);
			}
			
		}
		$this->layout_template = 'dialog';
		$this->layout->subtitle = 'SIGN UP';
		
		$this->view->render();
	}
}