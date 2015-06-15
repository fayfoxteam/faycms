<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Users;
use fay\core\Sql;
use fay\models\tables\Roles;
use fay\helpers\String;
use fay\models\Role;
use fay\models\Prop;
use fay\models\tables\Actionlogs;
use fay\common\ListView;
use fay\models\User;
use fay\models\Setting;
use fay\core\Response;
use fay\helpers\Html;
use fay\core\HttpException;
use fay\core\Loader;

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
			'cols'=>array('role', 'cellphone', 'email', 'cellphone', 'realname', 'reg_time'),
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
			'id > '.Users::ROLE_SYSTEM,
		));
		
		$sql = new Sql();
		$sql->from('users', 'u', '*')
			->joinLeft('roles', 'r', 'u.role = r.id', 'title AS role_title')
			->where('u.role > '.Users::ROLE_SYSTEM)
		;
		
		//超级管理员可以看到所有管理员
		//普通管理员即便有管理权限，也无法修改超级管理员
		if($this->session->get('role') != Users::ROLE_SUPERADMIN){
			$sql->where('r.is_show = 1');
		}
		
		if($this->input->get('keywords')){
			if($this->input->get('field') == 'id'){
				$sql->where(array(
					"u.id = ?"=>$this->input->get('keywords', 'intval'),
				));
			}else{
				$sql->where(array(
					"u.{$this->input->get('field')} LIKE ?"=>'%'.$this->input->get('keywords').'%',
				));
			}
		}
		
		if($this->input->get('role')){
			$sql->where(array(
				'u.role = ?'=>$this->input->get('role', 'intval'),
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
			->addRule(array(array('username', 'password', 'role'), 'required'));
		if($this->input->post()){
			
			if($this->form()->check()){
				$data = $this->form()->getFilteredData();
				$data['reg_time'] = $this->current_time;
				$data['status'] = Users::STATUS_VERIFIED;
				$data['salt'] = String::random('alnum', 5);
				$data['password'] = md5(md5($data['password']).$data['salt']);
				$user_id = Users::model()->insert($data);
				
				//设置属性
				$role = Role::model()->get($this->input->post('role', 'intval'));
				Prop::model()->createPropertySet('user_id', $user_id, $role['props'], $this->input->post('props'), array(
					'varchar'=>'fay\models\tables\ProfileVarchar',
					'int'=>'fay\models\tables\ProfileInt',
					'text'=>'fay\models\tables\ProfileText',
				));
				
				$this->actionlog(Actionlogs::TYPE_USERS, '添加了一个管理员', $user_id);
				
				Response::output('success', '管理员添加成功， '.Html::link('继续添加', array('admin/operator/create')), array('admin/operator/edit', array(
					'id'=>$user_id,
				)));
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		$this->view->roles = Roles::model()->fetchAll(array(
			'id > '.Users::ROLE_SYSTEM,
			'deleted = 0',
		), 'id,title');
		
		//附加属性
		$current_role = current($this->view->roles);
		$this->view->role = Role::model()->get($current_role['id']);
		
		$this->view->render();
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑管理员信息';
		$id = $this->input->request('id', 'intval');
		$this->form()->setScene('edit')
			->setModel(Users::model());
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
				Users::model()->update($data, $this->input->get('id'));
				
				//设置属性
				$role = Role::model()->get($this->input->post('role', 'intval'));
				Prop::model()->updatePropertySet('user_id', $id, $role['props'], $this->input->post('props'), array(
					'varchar'=>'fay\models\tables\ProfileVarchar',
					'int'=>'fay\models\tables\ProfileInt',
					'text'=>'fay\models\tables\ProfileText',
				));
				
				$this->actionlog(Actionlogs::TYPE_PROFILE, '编辑了管理员信息', $this->current_user);
				$this->flash->set('修改成功', 'success');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$this->view->user = User::model()->get($id, 'users.*,props.*');
		$this->form()->setData($this->view->user);
		
		$this->view->roles = Roles::model()->fetchAll(array(
			'id > '.Users::ROLE_SYSTEM,
			'deleted = 0',
		), 'id,title');	
		
		$this->view->role = Role::model()->get($this->view->user['role']);
		$this->view->render();
	}
	
	public function item(){
		if($id = $this->input->get('id', 'intval')){
			$this->view->user = User::model()->get($id, 'users.*');
		}else{
			throw new HttpException('参数不完整', 500);
		}
		
		$this->layout->subtitle = "用户 - {$this->view->user['username']}";
		
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		$this->view->render();
	}
	
	public function setStatus(){
		$id = $this->input->post('id', 'intval');
		
		$user = Users::model()->find($id, 'id,status,block');
		if(!$user){
			if($this->input->isAjaxRequest()){
				echo json_encode(array(
					'status'=>0,
					'message'=>'指定的用户ID不存在',
				));
			}else{
				throw new HttpException('指定的用户ID不存在');
			}
		}
		Users::model()->update($this->input->post(), $id, true);

		$this->actionlog(Actionlogs::TYPE_USERS, '编辑了管理员状态', $id);
		Response::output('success', array(
			'message'=>'管理员状态被编辑',
		));
	}
	
}