<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Users;
use fay\helpers\String;
use fay\models\tables\Actionlogs;
use fay\models\User;
use fay\models\Role;
use fay\models\Prop;

class ProfileController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'profile';
	}
	
	public function index(){
		$this->layout->subtitle = '编辑个人信息';
		$this->form()->setModel(Users::model());
		if($this->input->post()){
			if($this->form()->check()){
				//两次密码输入一致
				$data = Users::model()->setAttributes($this->input->post());
				if($password = $this->input->post('password')){
					//生成五位随机数
					$salt = String::random('alnum', 5);
					//密码加密
					$password = md5(md5($password).$salt);
					$data['salt'] = $salt;
					$data['password'] = $password;
				}else{
					unset($data['password']);
				}
				Users::model()->update($data, $this->current_user);
				$this->session->set('avatar', $data['avatar']);
				$this->session->set('nickname', $data['nickname']);
				
				//设置属性
				$role = Role::model()->get($this->session->get('role'));
				Prop::model()->updatePropertySet('user_id', $this->current_user, $role['props'], $this->input->post('props'), array(
					'varchar'=>'fay\models\tables\ProfileVarchar',
					'int'=>'fay\models\tables\ProfileInt',
					'text'=>'fay\models\tables\ProfileText',
				));
				
				$this->actionlog(Actionlogs::TYPE_PROFILE, '编辑了个人信息', $this->current_user);
				$this->flash->set('修改成功', 'success');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$this->view->user = User::model()->get($this->current_user);
		$this->form()->setData($this->view->user);
		
		$this->view->role = Role::model()->get($this->view->user['role']);
		$this->view->render();
	}
}