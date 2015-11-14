<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Users;
use fay\helpers\String;
use fay\models\tables\Actionlogs;
use fay\models\User;
use fay\models\Prop;
use fay\models\Flash;
use fay\models\tables\Props;
use fay\models\tables\Roles;

class ProfileController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'profile';
	}
	
	public function index(){
		$this->layout->subtitle = '编辑管理员信息';
		$id = $this->current_user;
		$this->form()->setModel(Users::model());
		if($this->input->post()){
			if($this->form()->check()){
				//两次密码输入一致
				$data = Users::model()->fillData($this->input->post());
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
				Users::model()->update($data, $id);
				
				//修改当前用户session
				\F::session()->set('avatar', $data['avatar']);
				\F::session()->set('nickname', $data['nickname']);
				
				//设置属性
				$roles = User::model()->getRoleIds($id);
				if($roles){
					$props = Prop::model()->mget($roles, Props::TYPE_ROLE);
					Prop::model()->updatePropertySet('user_id', $id, $props, $this->input->post('props'), array(
						'varchar'=>'fay\models\tables\UserPropVarchar',
						'int'=>'fay\models\tables\UserPropInt',
						'text'=>'fay\models\tables\UserPropText',
					));
				}
				
				$this->actionlog(Actionlogs::TYPE_PROFILE, '编辑了管理员信息', $id);
				Flash::set('修改成功', 'success');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$user = User::model()->get($id, 'users.*,props.*');
		$user_role_ids = User::model()->getRoleIds($id);
		$this->view->user = $user;
		$this->form()->setData($user['user'])
			->setData(array('roles'=>$user_role_ids));
		
		$this->view->roles = Roles::model()->fetchAll(array(
			'admin = 1',
			'deleted = 0',
		), 'id,title');
		
		$this->view->props = Prop::model()->mget($user_role_ids, Props::TYPE_ROLE);
		$this->view->render();
	}
}