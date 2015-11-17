<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Users;
use fay\core\Sql;
use fay\models\tables\Roles;
use fay\models\tables\Actionlogs;
use fay\common\ListView;
use fay\models\User;
use fay\models\Setting;
use fay\core\Response;
use fay\helpers\Html;
use fay\core\HttpException;
use fay\core\Loader;
use fay\models\Flash;
use fay\models\tables\UserProfile;

class OperatorController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'user';
	}
	
	public function index(){
		$this->layout->subtitle = '所有管理员';
			
		$this->layout->sublink = array(
			'uri'=>array('admin/operator/create'),
			'text'=>'添加管理员',
		);

		//自定义参数
		$this->layout->_setting_panel = '_setting_index';
		$_setting_key = 'admin_operator_index';
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
		
		//查询所有管理员类型
		$this->view->roles = Roles::model()->fetchAll(array(
			'deleted = 0',
			'admin = 1',
		));
		
		$sql = new Sql();
		$sql->from('users', 'u', '*')
			->joinLeft('user_profile', 'up', 'u.id = up.user_id', '*')
			->where('u.admin = 1')
		;
		
		if($this->input->get('keywords')){
			if($this->input->get('field') == 'id'){
				$sql->where(array(
					'u.id = ?'=>$this->input->get('keywords', 'intval'),
				));
			}else{
				$field = $this->input->get('field');
				if(in_array($field, Users::model()->getFields())){
					$sql->where(array(
						"u.{$field} LIKE ?"=>'%'.$this->input->get('keywords').'%',
					));
				}else{
					$sql->where(array(
						"up.{$field} LIKE ?"=>'%'.$this->input->get('keywords').'%',
					));
				}
			}
		}
		
		if($this->input->get('role')){
			$sql->joinLeft('users_roles', 'ur', 'u.id = ur.user_id')
				->where(array(
					'ur.role_id = ?' => $this->input->get('role', 'intval'),
				));
		}
		
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'asc' : 'desc';
			$sql->order("u.{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('u.id DESC');
		}
		
		$this->view->listview = new ListView($sql);
		
		$this->view->render();
	}
	
	public function create(){
		$this->layout->subtitle = '添加管理员';
		
		$this->form()->setScene('create')
			->setModel(Users::model())
			->setModel(UserProfile::model())
			->setRules(array(
				array(array('username', 'password'), 'required'),
				array('roles', 'int'),
			));
		if($this->input->post()){
			if($this->form()->check()){
				$data = Users::model()->fillData($this->input->post());
				isset($data['status']) || $data['status'] = Users::STATUS_VERIFIED;
				
				$extra = array(
					'trackid'=>'admin_create:'.\F::session()->get('user.id'),
					'roles'=>$this->input->post('roles', 'intval', array()),
					'props'=>$this->input->post('props', '', array()),
				);
				
				$user_id = User::model()->create($data, $extra);
				
				$this->actionlog(Actionlogs::TYPE_USERS, '添加了一个管理员', $user_id);
				
				Response::notify('success', '管理员添加成功， '.Html::link('继续添加', array('admin/operator/create', array(
					'roles'=>$this->input->post('roles', 'intval', array()),
				))), array('admin/operator/edit', array(
					'id'=>$user_id,
				)));
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		$this->view->roles = Roles::model()->fetchAll(array(
			'admin = 1',
			'deleted = 0',
		), 'id,title');
		
		$this->view->render();
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑管理员信息';
		$user_id = $this->input->request('id', 'intval');
		$this->form()->setScene('edit')
			->setModel(Users::model());
		if($this->input->post()){
			if($this->form()->check()){
				$data = Users::model()->fillData($this->input->post());
				
				$extra = array(
					'roles'=>$this->input->post('roles', 'intval', array()),
					'props'=>$this->input->post('props', '', array()),
				);
				
				User::model()->update($user_id, $data, $extra);
				
				$this->actionlog(Actionlogs::TYPE_PROFILE, '编辑了管理员信息', $user_id);
				Flash::set('修改成功', 'success');
				
				//置空密码字段
				$this->form()->setData(array('password'=>''), true);
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$user = User::model()->get($user_id, 'users.*,profile.*');
		$user_role_ids = User::model()->getRoleIds($user_id);
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
	
	public function item(){
		if($id = $this->input->get('id', 'intval')){
			$this->view->user = User::model()->get($id, 'users.*,props.*,roles.title,profile.*');
		}else{
			throw new HttpException('参数不完整', 500);
		}
		
		$this->layout->subtitle = "用户 - {$this->view->user['user']['username']}";
		
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		if($this->checkPermission('admin/operator/edit')){
			$this->layout->sublink = array(
				'uri'=>array('admin/operator/edit', array('id'=>$id)),
				'text'=>'编辑用户',
			);
		}
		
		$this->view->render();
	}
}