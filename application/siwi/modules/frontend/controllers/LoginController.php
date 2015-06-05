<?php
namespace siwi\modules\frontend\controllers;

use siwi\library\FrontController;
use fay\models\User;
use fay\models\tables\Users;
use fay\models\Email;
use fay\helpers\String;
use fay\core\Response;
use fay\core\Validator;
use fay\core\HttpException;

class LoginController extends FrontController{
	public function index(){
		if($this->input->post()){
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$result = User::model()->userLogin($username, $password);
			if($result['status']){
				if($this->input->get('redirect')){
					header('location:'.base64_decode($this->input->get('redirect')));
					die;
				}else{
					Response::redirect('user');
				}
			}else{
				$this->form()->setData(array(
					'username'=>$username,
				));
				$this->view->error = $result['message'];
			}
		}
		
		$this->layout_template = null;
		$this->view->render();
	}
	
	public function mini(){
		$this->layout_template = 'dialog';
		$this->layout->subtitle = 'SIGN IN';
		
		if($this->input->post()){
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$result = User::model()->userLogin($username, $password);
			if($result['status']){
				echo '<script>parent.common.afterLogin('.json_encode(array(
					'user_id'=>$result['user']['id'],
					'avatar'=>$result['user']['avatar'],
					'nickname'=>$result['user']['nickname'],
				)).');</script>';
				die;
			}else{
				$this->form()->setData(array(
					'username'=>$username,
				));
				$this->view->error = $result['message'];
			}
		}
		
		$this->view->render();
	}
	
	public function forgotPassword(){
		$this->layout->subtitle = array(
			'en'=>'Forget Password',
			'ch'=>'忘记密码',
		);
		
		if($email = $this->input->post('email')){
			$user = Users::model()->fetchRow(array(
				'username = ?'=>$email,
			), 'id');
			if(!$user){
				$this->flash->set('您所提交的Email未在本平台注册');
				Response::goback();
			}
			
			$active_key = String::random('alnum', 32);
			$url = $this->view->url('login/active', array(
				'email'=>$email,
				'active'=>$active_key,
			), false);
			Users::model()->update(array(
				'active_key'=>$active_key,
				'active_expire'=>$this->current_time + (3600 * 24),
			), $user['id']);
			
			$subject = '大赛平台密码找回';
			$body = "尊敬的{$email} ：
      因应您曾提出忘记登录密码事宜，大赛平台自动发出此电子邮件。<br>
      请在24小时内点击以下链接重新设置密码：（{$url}），您也可以将链接复制到浏览器地址栏进行访问。<br>
      感谢您对大赛平台的支持！";
			Email::model()->send($email, $subject, $body);
			$this->flash->set('邮件发送成功，请登陆您的邮箱查看！', 'success');
		}
		
		$this->view->render();
	}
	
	public function active(){
		$validator = new Validator();
		$check = $validator->check(array(
			array(array('active', 'email'), 'require'),
			array(array('email'), 'email')
		));
		
		if($check === true){
			$user = Users::model()->fetchRow(array(
				'active_key = ?'=>$this->input->get('active'),
				'active_expire > ?'=>$this->current_time,
				'email = ?'=>$this->input->get('email'),
			));
			if($user){
				$this->layout->subtitle = array(
					'en'=>'Reset Password',
					'ch'=>'密码重置',
				);
				
				if($this->input->post()){
					if($this->input->post('password') != $this->input->post('repassword')){
						$this->flash->set('两次输入密码不一致，请重新输入');
					}else{
						$salt = String::random('alnum', 5);
						$password = md5(md5($this->input->post('password')).$salt);
						
						Users::model()->update(array(
							'password'=>$password,
							'salt'=>$salt,
						), $user['id']);
						$this->flash->set('密码修改成功，请用新密码登陆', 'success');
					}
				}
			}else{
				throw new HttpException('链接地址参数不存在或已过期，<a href="'.$this->view->url('login/forgot-password').'">点此</a>重新发送找回密码邮件');
			}
		}else{
			throw new HttpException('异常的访问参数');
		}
		
		$this->view->render();
	}

}