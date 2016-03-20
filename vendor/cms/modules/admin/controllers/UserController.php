<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\Setting;
use fay\core\Sql;
use fay\models\tables\Users;
use fay\models\tables\Roles;
use fay\common\ListView;
use fay\models\User;
use fay\models\tables\Actionlogs;
use fay\core\Response;
use fay\helpers\Html;
use fay\core\HttpException;
use fay\core\Loader;
use fay\models\Flash;
use fay\models\tables\UserProfile;
use fay\models\user\Role;

class UserController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'user';
	}
	
	public function index(){
		$this->layout->subtitle = '所有用户';
			
		$this->layout->sublink = array(
			'uri'=>array('admin/user/create'),
			'text'=>'添加用户',
		);
		
		//自定义参数
		$this->layout->_setting_panel = '_setting_index';
		$_setting_key = 'admin_user_index';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'cols'=>array('roles', 'mobile', 'email', 'realname', 'reg_time'),
			'page_size'=>20,
		);
		$this->form('setting')->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
			));
		
		$sql = new Sql();
		$sql->from(array('u'=>'users'))
			->joinLeft(array('up'=>'user_profile'), 'u.id = up.user_id', '*')
			->where(array(
				'u.deleted = 0',
				'u.parent = 0',
				'u.admin = 0',
				'u.id > 10000',//10000以下的ID用于特殊用途，如系统提示等
			));
		
		if($this->input->get('keywords')){
			$sql->where(array(
				"u.{$this->input->get('select-by')} LIKE ?" => "%{$this->input->get('keywords')}%",
			));
		}

		if($this->input->get('role')){
			$sql->joinLeft(array('ur'=>'users_roles'), 'u.id = ur.user_id')
				->where(array(
					'ur.role_id = ?' => $this->input->get('role', 'intval'),
				));
		}else{
			$sql->where(array(
				'u.admin = 0'
			));
		}
		
		$time_field = $this->input->get('time_field');
		if($this->input->get('start_time')){
			$sql->where(array(
				"u.{$time_field} >= ?"=>$this->input->get('start_time','strtotime'),
			));
		}
		if($this->input->get('end_time')){
			$sql->where(array(
				"u.{$time_field} <= ?"=>$this->input->get('end_time','strtotime'),
			));
		}
		
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'asc' : 'desc';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('u.id DESC');
		}
		
		$this->view->roles = Roles::model()->fetchAll(array(
			'deleted = 0',
			'admin = 0',
		), 'id,title');
		
		$this->view->listview = new ListView($sql, array(
			'page_size'=>!empty($this->view->_settings['page_size']) ? $this->view->_settings['page_size'] : 20,
			'empty_text'=>'<tr><td colspan="'.(count($this->form('setting')->getData('cols')) + 1).'" align="center">无相关记录！</td></tr>',
		));
		
		//引入IP地址库
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		$this->view->render();
	}
	
	public function create(){
		$this->layout->subtitle = '添加用户';
		
		$this->form()->setScene('create')
			->setModel(Users::model())
			->setModel(UserProfile::model())
			->setRules(array(
				array(array('username', 'password'), 'required'),
				array('roles', 'int'),
			));
		if($this->input->post() && $this->form()->check()){
			isset($data['status']) || $data['status'] = Users::STATUS_VERIFIED;
			
			$extra = array(
				'trackid'=>'admin_create:'.\F::session()->get('user.id'),
				'roles'=>$this->input->post('roles', 'intval', array()),
				'props'=>$this->input->post('props', '', array()),
			);
			
			$user_id = User::model()->create($data, $extra);
			
			$this->actionlog(Actionlogs::TYPE_USERS, '添加了一个新用户', $user_id);
			
			Response::notify('success', '用户添加成功，'.Html::link('继续添加', array('admin/user/create', array(
				'roles'=>$this->input->post('roles', 'intval', array()),
			))), array('admin/user/edit', array(
				'id'=>$user_id,
			)));
		}
		
		$this->view->roles = Roles::model()->fetchAll(array(
			'admin = 0',
			'deleted = 0',
		), 'id,title');
		
		$this->view->render();
	}
	
	
	public function edit(){
		$this->layout->subtitle = '编辑用户';
		
		$user_id = $this->input->get('id', 'intval');
		$this->form()->setScene('edit')
			->setModel(Users::model());
		
		if($this->input->post() && $this->form()->check()){
			$data = Users::model()->fillData($this->form()->getAllData(false));
			
			$extra = array(
				'roles'=>$this->input->post('roles', 'intval', array()),
				'props'=>$this->input->post('props', '', array()),
			);
			
			User::model()->update($user_id, $data, $extra);
			
			$this->actionlog(Actionlogs::TYPE_USERS, '修改个人信息', $user_id);
			Flash::set('修改成功', 'success');
			
			//置空密码字段
			$this->form()->setData(array('password'=>''), true);
		}
		
		$user = User::model()->get($user_id, 'user.*,profile.*');
		$user_role_ids = Role::model()->getIds($user_id);
		$this->view->user = $user;
		$this->form()->setData($user['user'])
			->setData(array('roles'=>$user_role_ids));
		
		$this->view->roles = Roles::model()->fetchAll(array(
			'admin = 0',
			'deleted = 0',
		), 'id,title');
		
		$this->view->prop_set = User::model()->getPropertySet($user_id);
		$this->view->render();
	}
	
	public function item(){
		if($id = $this->input->get('id', 'intval')){
			$this->view->user = User::model()->get($id, 'user.*,props.*,roles.title,profile.*');
		}else{
			throw new HttpException('参数不完整', 500);
		}
		
		$this->layout->subtitle = "用户 - {$this->view->user['user']['username']}";
		
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		if($this->checkPermission('admin/user/edit')){
			$this->layout->sublink = array(
				'uri'=>array('admin/user/edit', array('id'=>$id)),
				'text'=>'编辑用户',
			);
		}
		
		$this->view->render();
	}
	
	public function getPropPanel(){
		$role_ids = $this->input->get('role_ids', 'intval', array());
		$user_id = $this->input->get('user_id', 'intval');
		
		if($role_ids){
			$props = User::model()->getPropsByRoles($role_ids);
		}else{
			$props = array();
		}
		
		if(!empty($props) && $user_id){
			$props = User::model()->getPropertySet($user_id, $props);
		}
		
		$this->view->prop_set = $props;
		
		$this->view->renderPartial('prop/_edit');
	}
}