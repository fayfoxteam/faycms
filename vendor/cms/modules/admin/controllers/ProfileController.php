<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Users;
use fay\models\tables\Actionlogs;
use fay\models\User;
use fay\models\Flash;
use fay\models\tables\Roles;
use fay\models\user\Role;

class ProfileController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'profile';
	}
	
	public function index(){
		$this->layout->subtitle = '编辑管理员信息';
		$user_id = $this->current_user;
		$this->form()->setModel(Users::model());
		if($this->input->post()){
			if($this->form()->check()){
				//两次密码输入一致
				$data = Users::model()->fillData($this->input->post());
				
				$extra = array(
					'props'=>$this->input->post('props', '', array()),
				);
				
				User::model()->update($user_id, $data, $extra);
				
				$this->actionlog(Actionlogs::TYPE_PROFILE, '编辑了管理员信息', $user_id);
				Flash::set('修改成功', 'success');
				
				//修改当前用户session
				\F::session()->set('avatar', $data['avatar']);
				\F::session()->set('nickname', $data['nickname']);
				
				//置空密码字段
				$this->form()->setData(array('password'=>''), true);
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$user = User::model()->get($user_id, 'user.*,profile.*');
		$user_role_ids = Role::model()->getIds($user_id);
		$this->view->user = $user;
		$this->form()->setData($user['user'])
			->setData(array('roles'=>$user_role_ids));
		
		$this->view->roles = Roles::model()->fetchAll(array(
			'admin = 1',
			'deleted = 0',
		), 'id,title');
		
		$this->view->prop_set = User::model()->getPropertySet($user_id);
		$this->view->render();
	}
}