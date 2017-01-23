<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\UsersTable;
use fay\models\tables\ActionlogsTable;
use fay\services\user\UserPropService;
use fay\services\user\UserService;
use fay\models\tables\RolesTable;
use fay\services\user\UserRoleService;
use fay\core\Response;

class ProfileController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'profile';
	}
	
	public function index(){
		$this->layout->subtitle = '编辑我的信息';
		$user_id = $this->current_user;
		$this->form()->setModel(UsersTable::model());
		if($this->input->post() && $this->form()->check()){
			//两次密码输入一致
			$data = UsersTable::model()->fillData($this->input->post());
			
			$extra = array(
				'props'=>$this->input->post('props', '', array()),
			);
			
			UserService::service()->update($user_id, $data, $extra);
			
			$this->actionlog(ActionlogsTable::TYPE_PROFILE, '编辑了自己的信息', $user_id);
			Response::notify('success', '修改成功', false);
			
			//置空密码字段
			$this->form()->setData(array('password'=>''), true);
		}
		
		$user = UserService::service()->get($user_id, 'user.*,profile.*');
		$user_role_ids = UserRoleService::service()->getIds($user_id);
		$this->view->user = $user;
		$this->form()->setData($user['user'])
			->setData(array('roles'=>$user_role_ids));
		
		$this->view->roles = RolesTable::model()->fetchAll(array(
			'admin = 1',
			'deleted = 0',
		), 'id,title');
		
		$this->view->prop_set = PropService::service()->getPropertySet($user_id);
		$this->view->render();
	}
}