<?php
namespace jxsj\modules\user\controllers;

use jxsj\library\UserController;
use fay\models\tables\Users;
use fay\helpers\StringHelper;
use fay\services\FlashService;

class ProfileController extends UserController{
	public function index(){
		$this->layout->subtitle = array(
			'en'=>'Profile',
			'ch'=>'个人资料'
		);
		
		if($this->input->post()){
			Users::model()->update(array(
				'email'=>$this->input->post('email'),
				'mobile'=>$this->input->post('mobile'),
				'realname'=>$this->input->post('realname'),
				'nickname'=>$this->input->post('nickname'),
			), $this->current_user);
			
			FlashService::set('个人资料修改成功', 'success');
		}
		
		$this->layout->current_directory = 'profile';
		$user = Users::model()->find(\F::session()->get('user.id'));
		$this->form()->setData($user);
		$this->view->render();
	}
	
	public function password(){
		$this->layout->subtitle = array(
			'en'=>'Password',
			'ch'=>'密码修改'
		);
		
		if($this->input->post()){
			if($this->input->post('password') != $this->input->post('repassword')){
				FlashService::set('两次密码不一致');
			}else{
				$user = Users::model()->find(\F::session()->get('user.id'), 'password,salt');
				if($user['password'] != md5(md5($this->input->post('old_password')).$user['salt'])){
					FlashService::set('原密码不正确');
				}else{
					$salt = StringHelper::random('alnum', 5);
					$password = md5(md5($this->input->post('password')).$salt);
					Users::model()->update(array(
						'password'=>$password,
						'salt'=>$salt,
					), \F::session()->get('user.id'));
					FlashService::set('密码修改成功', 'success');
				}
			}
		}
		
		$this->layout->current_directory = 'password';
		
		$this->view->render();
	}
}