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
use fay\models\tables\UserProfile;
use fay\helpers\Request;
use fay\models\tables\Props;
use fay\models\tables\UsersRoles;

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
			'cols'=>array('roles', 'cellphone', 'email', 'cellphone', 'realname', 'reg_time'),
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
			->joinLeft('user_profile', 'up', 'u.id = up.user_id', '*')
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
			$sql->joinLeft('users_roles', 'ur', 'u.id = ur.user_id')
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
		if($this->input->post()){
			if($this->form()->check()){
				$data = Users::model()->fillData($this->input->post());
				$data['status'] = Users::STATUS_VERIFIED;
				$data['salt'] = String::random('alnum', 5);
				$data['password'] = md5(md5($data['password']).$data['salt']);
				$data['admin'] = 0;
				//插用户表
				$user_id = Users::model()->insert($data);
				//插用户扩展表
				UserProfile::model()->insert(array(
					'user_id'=>$user_id,
					'reg_time'=>$this->current_time,
					'reg_ip'=>Request::ip2int(Request::getIP()),
					'trackid'=>'admin_create:'.\F::session()->get('user.id'),
				));
				//插角色表
				$roles = $this->input->post('roles', 'intval');
				if($roles){
					$user_roles = array();
					foreach($roles as $r){
						$user_roles[] = array(
							'user_id'=>$user_id,
							'role_id'=>$r,
						);
					}
					UsersRoles::model()->bulkInsert($user_roles);
				}
				
				//设置属性
				if($roles){
					$props = Prop::model()->mget($roles, Props::TYPE_ROLE);
					Prop::model()->updatePropertySet('user_id', $user_id, $props, $this->input->post('props'), array(
						'varchar'=>'fay\models\tables\UserPropVarchar',
						'int'=>'fay\models\tables\UserPropInt',
						'text'=>'fay\models\tables\UserPropText',
					));
				}
				
				$this->actionlog(Actionlogs::TYPE_USERS, '添加了一个新用户', $user_id);
				
				Response::notify('success', '用户添加成功，'.Html::link('继续添加', array('admin/user/create')), array('admin/user/edit', array(
					'id'=>$user_id,
				)));
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$this->view->roles = Roles::model()->fetchAll(array(
			'admin = 0',
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
				
				$roles = $this->form()->getData('roles');
				if(!empty($roles)){
					//删除被删除了的角色
					UsersRoles::model()->delete(array(
						'user_id = ?'=>$id,
						'role_id NOT IN (?)'=>$roles,
					));
					$user_roles = array();
					foreach($roles as $r){
						if(!UsersRoles::model()->fetchRow(array(
							'user_id = ?'=>$id,
							'role_id = ?'=>$r,
						))){
							//不存在，则插入
							$user_roles[] = array(
								'user_id'=>$id,
								'role_id'=>$r,
							);
						}
					}
					UsersRoles::model()->bulkInsert($user_roles);
				}else{
					//删除全部角色
					UsersRoles::model()->delete(array(
						'user_id = ?'=>$id,
					));
				}
				
				//设置属性
				if($roles){
					$props = Prop::model()->mget($roles, Props::TYPE_ROLE);
					Prop::model()->updatePropertySet('user_id', $id, $props, $this->input->post('props'), array(
						'varchar'=>'fay\models\tables\UserPropVarchar',
						'int'=>'fay\models\tables\UserPropInt',
						'text'=>'fay\models\tables\UserPropText',
					));
				}
				
				$this->actionlog(Actionlogs::TYPE_USERS, '修改个人信息', $id);
				Flash::set('修改成功', 'success');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$user = User::model()->get($id, 'users.*,props.*');
		$user['roles'] = User::model()->getRoleIds($user['id']);
		$this->view->user = $user;
		$this->form()->setData($user);
		
		$this->view->roles = Roles::model()->fetchAll(array(
			'admin = 0',
			'deleted = 0',
		), 'id,title');
		
		$this->view->props = Prop::model()->mget($user['roles'], Props::TYPE_ROLE);
		$this->view->render();
	}
	
	public function item(){
		if($id = $this->input->get('id', 'intval')){
			$this->view->user = User::model()->get($id, 'users.*,props.*,roles.*,profile.*');
		}else{
			throw new HttpException('参数不完整', 500);
		}
		
		$this->layout->subtitle = "用户 - {$this->view->user['username']}";
		
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
			$roles = Role::model()->get($role_ids);
			$this->view->props = $roles['props'];
		}else{
			$this->view->props = array();
		}
		
		if(!empty($roles) && $user_id){
			$this->view->data = User::model()->getProps($user_id, $roles['props']);
		}else{
			$this->view->data = array();
		}
		
		$this->view->renderPartial('prop/_edit');
	}
}