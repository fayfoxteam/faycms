<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Users;
use fay\core\Sql;
use fay\models\tables\Roles;
use fay\helpers\String;
use fay\models\Prop;
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
use fay\helpers\Request;
use fay\models\tables\UsersRoles;
use fay\models\tables\Props;

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
			'cols'=>array('roles', 'cellphone', 'email', 'cellphone', 'realname', 'reg_time'),
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
				$data = $this->form()->getFilteredData();
				$data['status'] = Users::STATUS_VERIFIED;
				$data['salt'] = String::random('alnum', 5);
				$data['password'] = md5(md5($data['password']).$data['salt']);
				$data['admin'] = 1;
				//插用户表
				$user_id = Users::model()->insert($data);
				//插用户扩展表
				UserProfile::model()->insert(array(
					'user_id'=>$user_id,
					'reg_time'=>$this->current_time,
					'reg_ip'=>Request::ip2int(Request::getIP()),
					'trackid'=>'admin_create:'.$this->session->get('id'),
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
				
				$this->actionlog(Actionlogs::TYPE_USERS, '添加了一个管理员', $user_id);
				
				Response::output('success', '管理员添加成功， '.Html::link('继续添加', array('admin/operator/create')), array('admin/operator/edit', array(
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
		$id = $this->input->request('id', 'intval');
		$this->form()->setScene('edit')
			->setModel(Users::model());
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
				
				$this->actionlog(Actionlogs::TYPE_PROFILE, '编辑了管理员信息', $id);
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
			'admin = 1',
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
		
		if($this->checkPermission('admin/operator/edit')){
			$this->layout->sublink = array(
				'uri'=>array('admin/operator/edit', array('id'=>$id)),
				'text'=>'编辑用户',
			);
		}
		
		$this->view->render();
	}
}