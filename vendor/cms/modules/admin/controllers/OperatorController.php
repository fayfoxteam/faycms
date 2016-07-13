<?php
namespace cms\modules\admin\controllers;

use cms\library\AdminController;
use fay\models\tables\Users;
use fay\core\Sql;
use fay\models\tables\Roles;
use fay\models\tables\Actionlogs;
use fay\common\ListView;
use fay\services\user\Prop;
use fay\services\User;
use fay\core\Response;
use fay\helpers\Html;
use fay\core\HttpException;
use fay\core\Loader;
use fay\models\tables\UserProfile;
use fay\services\user\Role;

class OperatorController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'user';
	}
	
	public function index(){
		//搜索条件验证，异常数据直接返回404
		$this->form()->setScene('final')->setRules(array(
			array('orderby', 'range', array(
				'range'=>array_merge(
					Users::model()->getFields(),
					UserProfile::model()->getFields()
				),
			)),
			array('order', 'range', array(
				'range'=>array('asc', 'desc'),
			)),
			array('keywords_field', 'range', array(
				'range'=>array_merge(
					Users::model()->getFields(),
					UserProfile::model()->getFields()
				),
			)),
		))->check();
		
		$this->layout->subtitle = '所有管理员';
			
		$this->layout->sublink = array(
			'uri'=>array('admin/operator/create'),
			'text'=>'添加管理员',
		);
		
		//页面设置
		$this->settingForm('admin_operator_index', '_setting_index', array(
			'cols'=>array('roles', 'mobile', 'email', 'realname', 'reg_time'),
			'page_size'=>20,
		));
		
		//查询所有管理员类型
		$this->view->roles = Roles::model()->fetchAll(array(
			'deleted = 0',
			'admin = 1',
		));
		
		$sql = new Sql();
		$sql->from(array('u'=>'users'), '*')
			->joinLeft(array('up'=>'user_profile'), 'u.id = up.user_id', '*')
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
			$sql->joinLeft(array('ur'=>'users_roles'), 'u.id = ur.user_id')
				->where(array(
					'ur.role_id = ?' => $this->input->get('role', 'intval'),
				));
		}
		
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'ASC' : 'DESC';
			$sql->order("{$this->view->orderby} {$this->view->order}");
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
		if($this->input->post() && $this->form()->check()){
			$data = Users::model()->fillData($this->input->post());
			isset($data['status']) || $data['status'] = Users::STATUS_VERIFIED;
			
			$extra = array(
				'profile'=>array(
					'trackid'=>'admin_create:'.\F::session()->get('user.id'),
				),
				'roles'=>$this->input->post('roles', 'intval', array()),
				'props'=>$this->input->post('props', '', array()),
			);
			
			$user_id = User::service()->create($data, $extra, 1);
			
			$this->actionlog(Actionlogs::TYPE_USERS, '添加了一个管理员', $user_id);
			
			Response::notify('success', '管理员添加成功， '.Html::link('继续添加', array('admin/operator/create', array(
				'roles'=>$this->input->post('roles', 'intval', array()),
			))), array('admin/operator/edit', array(
				'id'=>$user_id,
			)));
		}
		$this->view->roles = Roles::model()->fetchAll(array(
			'admin = 1',
			'deleted = 0',
		), 'id,title');
		
		//有可能默认了某些角色
		$role_ids = $this->input->get('roles', 'intval');
		if($role_ids){
			$this->view->prop_set = Prop::service()->getByRefer($role_ids);
		}else{
			$this->view->prop_set = array();
		}
		
		$this->view->render();
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑管理员信息';
		$user_id = $this->input->request('id', 'intval');
		$this->form()->setScene('edit')
			->setModel(Users::model());
		if($this->input->post() && $this->form()->check()){
			$data = Users::model()->fillData($this->input->post());
			
			$extra = array(
				'roles'=>$this->input->post('roles', 'intval', array()),
				'props'=>$this->input->post('props', '', array()),
			);
			
			User::service()->update($user_id, $data, $extra);
			
			$this->actionlog(Actionlogs::TYPE_PROFILE, '编辑了管理员信息', $user_id);
			Response::notify('success', '修改成功', false);
			
			//置空密码字段
			$this->form()->setData(array('password'=>''), true);
		}
		
		$user = User::service()->get($user_id, 'user.*,profile.*');
		$user_role_ids = Role::service()->getIds($user_id);
		$this->view->user = $user;
		$this->form()->setData($user['user'])
			->setData(array('roles'=>$user_role_ids));
		
		$this->view->roles = Roles::model()->fetchAll(array(
			'admin = 1',
			'deleted = 0',
		), 'id,title');	
		
		$this->view->prop_set = Prop::service()->getPropertySet($user_id);
		$this->view->render();
	}
	
	public function item(){
		if($id = $this->input->get('id', 'intval')){
			$this->view->user = User::service()->get($id, 'user.*,props.*,roles.title,profile.*');
		}else{
			throw new HttpException('参数不完整', 500);
		}
		
		$this->layout->subtitle = "管理员 - {$this->view->user['user']['username']}";
		
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		if($this->checkPermission('admin/operator/edit')){
			$this->layout->sublink = array(
				'uri'=>array('admin/operator/edit', array('id'=>$id)),
				'text'=>'编辑管理员',
			);
		}
		
		$this->view->render();
	}
}