<?php
namespace cms\modules\admin\controllers;

use fay\core\Controller;
use fay\models\Log;
use fay\core\Response;
use fay\models\tables\Logs;
use fay\core\Loader;
use fay\services\User;

class LoginController extends Controller{
	public function __construct(){
		parent::__construct();
		$this->config->set('session.namespace', $this->config->get('session.namespace').'_admin');
		
		$this->current_user = \F::session()->get('user.id', 0);
	}
	
	public function index(){
		//不显示debug信息，显示debug信息的话视觉效果上不好看
		$this->config->set('debug', false);
		
		if($this->input->post()){
			$result = User::model()->checkPassword(
				$this->input->post('username'),
				$this->input->post('password'),
				true
			);
			
			if($result['user_id']){
				$user = User::model()->login($result['user_id']);
			}else{
				Response::notify('error', array(
					'message'=>isset($result['message']) ? $result['message'] : '登录失败',
					'code'=>isset($result['error_code']) ? $result['error_code'] : '',
				));
			}
			if($user){
				Log::set('admin:action:login.success', array(
					'fmac'=>isset($_COOKIE['fmac']) ? $_COOKIE['fmac'] : '',
					'username'=>$this->input->post('username'),
				));
				if($this->input->get('redirect')){
					header('location:'.base64_decode($this->input->get('redirect')));
					die;
				}else{
					Response::redirect('admin/index/index');
				}
			}else{
				Log::set('admin:action:login.fail', array(
					'error_code'=>$result['error_code'],
					'username'=>$this->input->post('username'),
					'password'=>$this->input->post('password'),
				), Logs::TYPE_WARMING);
				$this->view->error = $result['message'];
			}
		}
		
		//引入IP地址库
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		$this->view->render('index');
	}
	
	public function logout(){
		User::model()->logout();
		Response::redirect('admin/login/index');
	}
}