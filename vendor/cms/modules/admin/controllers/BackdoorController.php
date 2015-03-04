<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Users;
use fay\core\Response;

class BackdoorController extends AdminController{
	public function __construct(){
		parent::__construct();
		error_reporting(E_ALL);
	}
	
	public function login(){
		$user = Users::model()->find($this->input->get('id'));
		pr($user);
		if($user['role'] > 30){
			
		}else{
			$this->session->set('id', $user['id']);
			$this->session->set('username', $user['username']);
			$this->session->set('email', $user['email']);
			$this->session->set('cellphone', $user['cellphone']);
			$this->session->set('role', $user['role']);
			$this->session->set('realname', $user['realname']);
			$this->session->set('last_login_time', $user['last_login_time']);
			Response::redirect();
		}
	}
	
}