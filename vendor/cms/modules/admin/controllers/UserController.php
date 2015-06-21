<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\Setting;
use fay\core\Sql;
use fay\models\tables\Users;
use fay\models\tables\Roles;
use fay\common\ListView;
use fay\models\User;
use fay\helpers\String;
use fay\models\Role;
use fay\models\Prop;
use fay\models\tables\Actionlogs;
use fay\core\Response;
use fay\helpers\Html;
use fay\core\HttpException;
use fay\core\Loader;
use fay\models\Flash;

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
			'cols'=>array('role', 'cellphone', 'email', 'cellphone', 'realname', 'reg_time'),
			'page_size'=>20,
		);
		$this->form('setting')->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
			));
		
		$sql = new Sql();
		$sql->from('users', 'u')
			->joinLeft('roles', 'r', 'u.role = r.id', 'title AS role_title')
			->where(array(
				'u.id > 10000',//10000以下的ID用于特殊用途，如系统提示等
				'u.parent = 0',
				'u.deleted = 0',
				'r.is_show = 1',
			));
		
		if($this->input->get('keywords')){
			$sql->where(array(
				"u.{$this->input->get('select-by')} LIKE ?" => "%{$this->input->get('keywords')}%",
			));
		}

		if($this->input->get('role')){
			$sql->where(array(
				'u.role = ?' => $this->input->get('role', 'intval'),
			));
		}else{
			$sql->where(array(
				'u.role < '.Users::ROLE_SYSTEM,
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
			'id < '.Users::ROLE_SYSTEM,
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
			->addRule(array(array('username', 'password', 'role'), 'required'));
		
		if($this->input->post()){
			if($this->form()->check()){
				$data = Users::model()->setAttributes($this->input->post());
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
				$this->actionlog(Actionlogs::TYPE_USERS, '添加了一个新用户', $user_id);
				Response::output('success', '用户添加成功，'.Html::link('继续添加', array('admin/user/create')), array('admin/user/edit', array(
					'id'=>$user_id,
				)));
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$this->view->roles = Roles::model()->fetchAll(array(
			'id < '.Users::ROLE_SYSTEM,
			'deleted = 0',
		), 'id,title');
		
		//附加属性
		$current_role = current($this->view->roles);
		$this->view->role = Role::model()->get($current_role['id']);

		$this->view->render();
	}
	
	
	public function edit(){
		$this->layout->subtitle = '编辑用户';
		
		$id = $this->input->get('id', 'intval');
		$this->form()->setScene('edit')
			->setModel(Users::model());
		
		if($this->input->post()){
			if($this->form()->check()){
				$data = Users::model()->setAttributes($this->input->post());
				if($password = $this->input->post('password')){
					$salt = String::random('alnum', 5);
					//密码加密
					$password = md5(md5($password).$salt);
					$data['salt'] = $salt;
					$data['password'] = $password;
				}else{
					unset($data['password']);
				}
				Users::model()->update($data, $id);

				//设置属性
				$role = Role::model()->get($this->input->post('role', 'intval'));
				Prop::model()->updatePropertySet('user_id', $id, $role['props'], $this->input->post('props'), array(
					'varchar'=>'fay\models\tables\ProfileVarchar',
					'int'=>'fay\models\tables\ProfileInt',
					'text'=>'fay\models\tables\ProfileText',
				));
				
				$this->actionlog(Actionlogs::TYPE_USERS, '修改个人信息', $id);
				Flash::set('修改成功', 'success');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$this->view->user = User::model()->get($id, 'users.*,props.*');
		$this->form()->setData($this->view->user);
		
		$this->view->roles = Roles::model()->fetchAll(array(
			'id < '.Users::ROLE_SYSTEM,
			'deleted = 0',
		), 'id,title');
		
		$this->view->role = Role::model()->get($this->view->user['role']);
		
		$this->view->render();
	}
	
	public function item(){
		if($id = $this->input->get('id', 'intval')){
			$this->view->user = User::model()->get($id, 'users.*,props.*');
		}else if($username = $this->input->get('username')){
			$user = Users::model()->fetchRow(array(
				'username = ?'=>$username,
			), 'id');
			$this->view->user = User::model()->get($user['id'], 'users.*,props.*');
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

		$this->actionlog(Actionlogs::TYPE_USERS, '编辑了用户状态', $id);
		
		Response::output('success', array(
			'message'=>'一个用户状态被编辑',
		));
	}
	
	public function getPropPanel(){
		$role = Role::model()->get($this->input->get('role_id', 'intval'));
		$this->view->props = $role['props'];
		
		$user_id = $this->input->get('user_id', 'intval');
		if($user_id){
			$this->view->data = User::model()->getProps($user_id, $this->view->props);
		}else{
			$this->view->data = array();
		}
		
		$this->view->renderPartial('prop/_edit');
	}
}