<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\core\Sql;
use fay\models\tables\UsersTable;
use fay\models\tables\RolesTable;
use fay\common\ListView;
use fay\services\user\UserPropService;
use fay\services\user\UserService;
use fay\models\tables\ActionlogsTable;
use fay\core\Response;
use fay\helpers\HtmlHelper;
use fay\core\HttpException;
use fay\core\Loader;
use fay\models\tables\UserProfileTable;
use fay\services\user\UserRoleService;

class UserController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'user';
	}
	
	public function index(){
		//搜索条件验证，异常数据直接返回404
		$this->form('search')->setScene('final')->setRules(array(
			array('orderby', 'range', array(
				'range'=>array_merge(
					UsersTable::model()->getFields(),
					UserProfileTable::model()->getFields()
				),
			)),
			array('order', 'range', array(
				'range'=>array('asc', 'desc'),
			)),
			array('keywords_field', 'range', array(
				'range'=>array_merge(
					UsersTable::model()->getFields(),
					UserProfileTable::model()->getFields()
				),
			)),
		))->check();
		
		$this->layout->subtitle = '所有用户';
			
		$this->layout->sublink = array(
			'uri'=>array('cms/admin/user/create'),
			'text'=>'添加用户',
		);
		
		//页面设置
		$this->settingForm('admin_user_index', '_setting_index', array(
			'cols'=>array('roles', 'mobile', 'email', 'realname', 'reg_time'),
			'page_size'=>20,
		));
		
		$sql = new Sql();
		$sql->from(array('u'=>'users'))
			->joinLeft(array('up'=>'user_profile'), 'u.id = up.user_id', '*')
			->where(array(
				'u.delete_time = 0',
				'u.parent = 0',
				'u.admin = 0',
				'u.id > 10000',//10000以下的ID用于特殊用途，如系统提示等
			));
		
		if($this->input->get('keywords')){
			$sql->where(array(
				"u.{$this->input->get('keywords_field')} LIKE ?" => "%{$this->input->get('keywords')}%",
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
			$this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('u.id DESC');
		}
		
		$this->view->roles = RolesTable::model()->fetchAll(array(
			'delete_time = 0',
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
			->setModel(UsersTable::model())
			->setModel(UserProfileTable::model())
			->setRules(array(
				array(array('username', 'password'), 'required'),
				array('roles', 'int'),
			));
		if($this->input->post() && $this->form()->check()){
			$data = UsersTable::model()->fillData($this->input->post());
			isset($data['status']) || $data['status'] = UsersTable::STATUS_VERIFIED;
			
			$extra = array(
				'profile'=>array(
					'trackid'=>'admin_create:'.\F::session()->get('user.id'),
				),
				'roles'=>$this->input->post('roles', 'intval', array()),
				'props'=>$this->input->post('props', '', array()),
			);
			
			$user_id = UserService::service()->create($data, $extra);
			
			$this->actionlog(ActionlogsTable::TYPE_USERS, '添加了一个新用户', $user_id);
			
			Response::notify('success', '用户添加成功，'.HtmlHelper::link('继续添加', array('cms/admin/user/create', array(
				'roles'=>$this->input->post('roles', 'intval', array()),
			))), array('cms/admin/user/edit', array(
				'id'=>$user_id,
			)));
		}
		
		$this->view->roles = RolesTable::model()->fetchAll(array(
			'admin = 0',
			'delete_time = 0',
		), 'id,title');
		
		$this->view->render();
	}
	
	
	public function edit(){
		$this->layout->subtitle = '编辑用户';
		
		$user_id = $this->input->get('id', 'intval');
		$this->form()->setScene('edit')
			->setModel(UsersTable::model());
		
		if($this->input->post() && $this->form()->check()){
			$data = UsersTable::model()->fillData($this->form()->getAllData(false));
			
			$extra = array(
				'roles'=>$this->input->post('roles', 'intval', array()),
				'props'=>$this->input->post('props', '', array()),
			);
			
			UserService::service()->update($user_id, $data, $extra);
			
			$this->actionlog(ActionlogsTable::TYPE_USERS, '修改个人信息', $user_id);
			Response::notify('success', '编辑成功', false);
			
			//置空密码字段
			$this->form()->setData(array('password'=>''), true);
		}
		
		$user = UserService::service()->get($user_id, 'user.*,profile.*');
		$user_role_ids = UserRoleService::service()->getIds($user_id);
		$this->view->user = $user;
		$this->form()->setData($user['user'])
			->setData(array('roles'=>$user_role_ids));
		
		$this->view->roles = RolesTable::model()->fetchAll(array(
			'admin = 0',
			'delete_time = 0',
		), 'id,title');
		
		$this->view->prop_set = UserPropService::service()->getPropertySet($user_id);
		$this->view->render();
	}
	
	public function item(){
		if($id = $this->input->get('id', 'intval')){
			$this->view->user = UserService::service()->get($id, 'user.*,props.*,roles.title,profile.*');
		}else{
			throw new HttpException('参数不完整', 500);
		}
		
		$this->layout->subtitle = "用户 - {$this->view->user['user']['username']}";
		
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		if($this->checkPermission('cms/admin/user/edit')){
			$this->layout->sublink = array(
				'uri'=>array('cms/admin/user/edit', array('id'=>$id)),
				'text'=>'编辑用户',
			);
		}
		
		$this->view->render();
	}
	
	public function getPropPanel(){
		$role_ids = $this->input->get('role_ids', 'intval', array());
		$user_id = $this->input->get('user_id', 'intval');
		
		if($role_ids){
			$props = UserPropService::service()->getByRefer($role_ids);
		}else{
			$props = array();
		}
		
		if(!empty($props) && $user_id){
			$prop_set = UserPropService::service()->getPropertySet($user_id, $props);
		}else{
			$prop_set = $props;
		}
		
		$this->view->renderPartial('prop/_edit', array(
			'prop_set'=>$prop_set,
		));
	}
}