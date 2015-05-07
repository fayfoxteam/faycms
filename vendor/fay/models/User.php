<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Users;
use fay\models\tables\Roles;
use fay\models\tables\Props;
use fay\helpers\RequestHelper;

class User extends Model{
	/**
	 * @param string $className
	 * @return User
	 */
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	public function adminLogin($username, $password){
		if(empty($username)){
			return array(
				'status'=>0,
				'message'=>'用户名不能为空！',
				'error_code'=>'username:can-not-be-empty',
			);
		}
		if(empty($password)){
			return array(
				'status'=>0,
				'message'=>'密码不能为空！',
				'error_code'=>'password:can-not-be-empty',
			);
		}
		$conditions = array(
			'username = ?'=>$username,
			'deleted = 0',
		);
		$user = Users::model()->fetchRow($conditions);
		//判断用户名是否存在
		if(!$user){
			return array(
				'status'=>0,
				'message'=>'用户名不存在！',
				'error_code'=>'username:not-exist',
			);
		}
		$password = md5(md5($password).$user['salt']);
		if($password != $user['password']){
			return array(
				'status'=>0,
				'message'=>'密码错误！',
				'error_code'=>'password:not-match',
			);
		}
		
		if($user['role'] < Users::ROLE_SYSTEM){
			return array(
				'status'=>0,
				'message'=>'您不是管理员，不能登陆！',
				'error_code'=>'not-admin',
			);
		}
		
		if($user['block']){
			return array(
				'status'=>0,
				'message'=>'用户已锁定！',
				'error_code'=>'block:blocked',
			);
		}
		
		\F::session()->set('id', $user['id']);
		\F::session()->set('username', $user['username']);
		\F::session()->set('nickname', $user['nickname']);
		\F::session()->set('role', $user['role']);
		\F::session()->set('last_login_time', $user['last_login_time']);
		\F::session()->set('last_login_ip', long2ip($user['last_login_ip']));
		\F::session()->set('status', $user['status']);
		\F::session()->set('avatar', $user['avatar']);
		
		//获取角色名称
		$role = Roles::model()->find($user['role']);
		\F::session()->set('role_title', $role['title']);
		//设置权限，超级管理员无需设置
		if($user['role'] != Users::ROLE_SUPERADMIN){
			$sql = "SELECT
				{$this->db->actions}.router
				FROM
				{$this->db->role_actions}
				LEFT JOIN {$this->db->actions} ON {$this->db->role_actions}.action_id = {$this->db->actions}.id
				WHERE
				{$this->db->role_actions}.role_id = ".$user['role'];
			$actions = $this->db->fetchAll($sql);
			$action_routers = array();
			foreach($actions as $a){
				$action_routers[] = $a['router'];
			}
			\F::session()->set('actions', $action_routers);
		}
		
		Users::model()->update(array(
			'last_login_ip'=>RequestHelper::ip2int(\F::app()->ip),
			'last_login_time'=>\F::app()->current_time,
			'last_time_online'=>\F::app()->current_time,
			'login_times'=>$user['login_times'] + 1,
		), $user['id']);
		
		return array(
			'status'=>1,
			'user'=>$user,
		);
			
	}
	
	public function userLogin($username, $password, $role = null){
		if($username == ''){
			return array(
				'status'=>0,
				'message'=>'用户名不能为空！',
				'error_code'=>'username:can-not-be-empty',
			);
		}
		if($password == ''){
			return array(
				'status'=>0,
				'message'=>'密码不能为空！',
				'error_code'=>'password:can-not-be-empty',
			);
		}
		$conditions = array(
			'username = ?'=>$username,
			'deleted = 0',
		);
		$user = Users::model()->fetchRow($conditions);
		//判断用户名是否存在
		if(!$user){
			return array(
				'status'=>0,
				'message'=>'用户名不存在！',
				'error_code'=>'username:not-exist',
			);
		}
		$password = md5(md5($password).$user['salt']);
		if($password != $user['password']){
			return array(
				'status'=>0,
				'message'=>'密码错误！',
				'error_code'=>'password:not-match',
			);
		}
		
		if($user['block']){
			return array(
				'status'=>0,
				'message'=>'用户已锁定！',
				'error_code'=>'block:blocked',
			);
		}
		
		if($user['status'] == Users::STATUS_UNCOMPLETED){
			return array(
				'status'=>0,
				'message'=>'账号信息不完整，请走完注册流程',
				'error_code'=>'status:uncompleted',
			);
		}else if($user['status'] == Users::STATUS_PENDING){
			return array(
				'status'=>0,
				'message'=>'您的账号正在审核中，请稍后重试！',
				'error_code'=>'status:pending',
			);
		}else if($user['status'] == Users::STATUS_VERIFY_FAILED){
			return array(
				'status'=>0,
				'message'=>'您的账号未通过人工审核，请修改资料后重新提交审核！',
				'error_code'=>'status:verify-failed',
			);
		}else if($user['status'] == Users::STATUS_NOT_VERIFIED){
			return array(
				'status'=>0,
				'message'=>'请先验证邮箱！',
				'error_code'=>'status:not-verified',
			);
		}
		
		$this->setSessionInfo($user);
		
		Users::model()->update(array(
			'last_login_ip'=>RequestHelper::ip2int(\F::app()->ip),
			'last_login_time'=>\F::app()->current_time,
			'last_time_online'=>\F::app()->current_time,
			'login_times'=>$user['login_times'] + 1,
		),'id = '.$user['id']);
		
		return array(
			'status'=>1,
			'user'=>$user,
		);
		
	}
	
	public function setSessionInfo($user){
		\F::session()->set('id', $user['id']);
		\F::session()->set('username', $user['username']);
		\F::session()->set('nickname', $user['nickname']);
		\F::session()->set('avatar', $user['avatar']);
		\F::session()->set('role', $user['role']);
		\F::session()->set('last_login_time', $user['last_login_time']);
		\F::session()->set('last_login_ip', long2ip($user['last_login_ip']));
		\F::session()->set('status', $user['status']);
	}
	
	public function logout(){
		\F::session()->remove();
	}
	
	public function get($id, $fields = 'props'){
		$fields = explode(',', $fields);
		$user = Users::model()->find($id, '!password,salt');
		
		if(!$user){
			return false;
		}
		
		if(in_array('props', $fields)){
			$props = Props::model()->fetchAll(array(
				'refer = ?'=>$user['role'],
				'type = '.Props::TYPE_ROLE,
				'deleted = 0',
			), 'id,title,element,required,is_show,alias', 'sort');
			
			$user['props'] = $this->getProps($id, $props);
		}
		
		return $user;
	}


	/**
	 * 获取用户附加属性<br>
	 * 可传入props（并不一定真的是当前用户分类对应的属性，比如编辑用户所属分类的时候会传入其他属性）<br>
	 * 若不传入，则会自动获取当前用户所属分类的属性集
	 */
	public function getProps($user_id, $props = array()){
		if(!$props){
			$user = Users::model()->find($user_id, 'role');
			$props = Prop::model()->getAll($user['role'], Props::TYPE_ROLE);
		}
	
		return Prop::model()->getPropertySet('user_id', $user_id, $props, array(
			'varchar'=>'fay\models\tables\ProfileVarchar',
			'int'=>'fay\models\tables\ProfileInt',
			'text'=>'fay\models\tables\ProfileText',
		));
	}
	
	/**
	 * 设置一个用户属性值
	 * @param int $user_id
	 * @param string $alias
	 * @param mix $value
	 * @return boolean
	 */
	public function setPropValueByAlias($alias, $value, $user_id = null){
		$user_id === null && $user_id = \F::app()->current_user;
		return Prop::model()->setPropValueByAlias('user_id', $user_id, $alias, $value, array(
			'varchar'=>'fay\models\tables\ProfileVarchar',
			'int'=>'fay\models\tables\ProfileInt',
			'text'=>'fay\models\tables\ProfileText',
		));
	}
	
	/**
	 * 获取一个用户属性值
	 * @param int $user_id
	 * @param string $alias
	 */
	public function getPropValueByAlias($alias, $user_id = null){
		$user_id === null && $user_id = \F::app()->current_user;
		return Prop::model()->getPropValueByAlias('user_id', $user_id, $alias, array(
			'varchar'=>'fay\models\tables\ProfileVarchar',
			'int'=>'fay\models\tables\ProfileInt',
			'text'=>'fay\models\tables\ProfileText',
		));
	}
	
	public function getPropOptionsByAlias($alias){
		return Prop::model()->getPropOptionsByAlias($alias);
	}
	
	public function getMemberCount($parent){
		$member = Users::model()->fetchRow(array(
			'parent = ?'=>$parent,
		), 'COUNT(*) AS count');
		return $member['count'];
	}
}