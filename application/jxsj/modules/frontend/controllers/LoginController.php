<?php
namespace jxsj\modules\frontend\controllers;

use jxsj\library\FrontController;
use fay\models\User;
use fay\core\Response;

class LoginController extends FrontController{
	public function index(){
		if($this->input->post()){
			if($this->input->post('vcode') && ($this->input->post('vcode', 'strtolower') != $this->session->get('vcode'))){
				Response::output('error', '验证码不正确', array('login'));
			}
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$result = User::model()->userLogin($username, $password);
			if($result['status']){
				if($this->input->get('redirect')){
					header('location:'.base64_decode($this->input->get('redirect')));
					die;
				}else{
					Response::redirect('user/exam');
				}
			}else{
				if($this->input->get('redirect')){
					Response::output('error', $result['message'], array('login', array(
						'redirect'=>$this->input->get('redirect', null, false),
					)));
				}else{
					Response::output('error', $result['message'], array('login'));
				}
			}
		}
		
		$this->layout_template = null;
		$this->view->render();
	}
}