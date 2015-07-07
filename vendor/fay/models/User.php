<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Users;
use fay\models\tables\Roles;
use fay\models\tables\Props;
use fay\helpers\Request;
use fay\models\tables\RolesCats;
use fay\helpers\SqlHelper;

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
		$user = Users::model()->fetchRow(array(
			'username = ?'=>$username,
			'deleted = 0',
		));
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
				{$this->db->roles_actions}
				LEFT JOIN {$this->db->actions} ON {$this->db->roles_actions}.action_id = {$this->db->actions}.id
				WHERE
				{$this->db->roles_actions}.role_id = ".$user['role'];
			$actions = $this->db->fetchAll($sql);
			$action_routers = array();
			foreach($actions as $a){
				$action_routers[] = $a['router'];
			}
			\F::session()->set('actions', $action_routers);
			
			//分类权限
			if(Option::get('system:role_cats')){
				//未分类文章任何人都有权限编辑
				$post_root = Category::model()->get('_system_post', 'id');
				\F::session()->set('role_cats', array_merge(array(0, $post_root['id']), RolesCats::model()->fetchCol('cat_id', 'role_id = '.$user['role'])));
			}
		}
		
		Users::model()->update(array(
			'last_login_ip'=>Request::ip2int(\F::app()->ip),
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
			'last_login_ip'=>Request::ip2int(\F::app()->ip),
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
	
	/**
	 * 返回单个用户
	 * @param string|array $ids 可以是逗号分割的id串，也可以是一维数组
	 *   若传入的$ids是一个数字，会返回一维数组（除props字段）
	 *   若传入多个id，则返回数组会与传入id顺序一致并以id为数组键
	 * @param string $fields 可指定返回字段
	 *   users.*系列可指定users表返回字段，若有一项为'users.*'，则返回除密码字段外的所有字段
	 *   props.*系列可指定返回哪些角色属性，若有一项为'props.*'，则返回所有角色属性
	 */
	public function get($id, $fields = 'users.username,users.nickname,users.id,users.avatar'){
		//解析$fields
		$fields = SqlHelper::processFields($fields, 'users');
		if(empty($fields['users'])){
			//若未指定返回字段，初始化
			$fields['users'] = array(
				'id', 'role', 'username', 'nickname',
			);
		}else if(in_array('*', $fields['users'])){
			//若存在*，视为全字段搜索，但密码字段不会被返回
			$labels = Users::model()->labels();
			unset($labels['password'], $labels['salt']);
			$fields['users'] = array_keys($labels);
		}else{
			//永远不会返回密码字段
			foreach($fields['users'] as $k => $v){
				if($v == 'password' || $v == 'salt'){
					unset($fields['users'][$k]);
				}
			}
		}
		
		$user = Users::model()->find($id, implode(',', empty($fields['props']) ? $fields['users'] : array_merge($fields['users'], array('id', 'role'))));
		
		if(!empty($fields['props'])){
			//附加角色属性
			$props = Props::model()->fetchAll(array(
				'refer = ?'=>$user['role'],
				'type = '.Props::TYPE_ROLE,
				'deleted = 0',
				'alias IN (?)'=>in_array('*', $fields['props']) ? false : $fields['props'],
			), 'id,title,element,required,is_show,alias', 'sort');
			
			$user['props'] = $this->getProps($user['id'], $props);
		}
		
		//删除不需要返回的字段
		if(!in_array('id', $fields['users'])){
			unset($user['id']);
		}
		if(!in_array('role', $fields['users'])){
			unset($user['role']);
		}
		
		return $user;
	}
	
	/**
	 * 返回多个用户
	 * @param string|array $ids 可以是逗号分割的id串，也可以是一维数组
	 *   若传入的$ids是一个数字，会返回一维数组（除props字段）
	 *   若传入多个id，则返回数组会与传入id顺序一致并以id为数组键
	 * @param string $fields 可指定返回字段
	 *   users.*系列可指定users表返回字段，若有一项为'users.*'，则返回除密码字段外的所有字段
	 *   props.*系列可指定返回哪些角色属性，若有一项为'props.*'，则返回所有角色属性（星号指代的是角色属性的别名）
	 */
	public function getByIds($ids, $fields = 'users.username,users.nickname,users.id,users.avatar'){
		//解析$ids
		is_array($ids) || $ids = explode(',', $ids);
		
		//解析$fields
		$fields = SqlHelper::processFields($fields, 'users');
		if(empty($fields['users'])){
			//若未指定返回字段，初始化
			$fields['users'] = array(
				'id', 'role', 'username', 'nickname',
			);
		}else if(in_array('*', $fields['users'])){
			//若存在*，视为全字段搜索，但密码字段不会被返回
			$labels = Users::model()->labels();
			unset($labels['password'], $labels['salt']);
			$fields['users'] = array_keys($labels);
		}else{
			//永远不会返回密码字段
			foreach($fields['users'] as $k => $v){
				if($v == 'password' || $v == 'salt'){
					unset($fields['users'][$k]);
				}
			}
		}
		
		$users = Users::model()->fetchAll(array(
			'id IN (?)'=>$ids,
		), implode(',', empty($fields['props']) ? $fields['users'] : array_merge($fields['users'], array('id', 'role'))));
		
		if(!empty($fields['props'])){
			//附加角色属性
			foreach($users as &$user){
				$props = Props::model()->fetchAll(array(
					'refer = ?'=>$user['role'],
					'type = '.Props::TYPE_ROLE,
					'deleted = 0',
					'alias IN (?)'=>in_array('*', $fields['props']) ? false : $fields['props'],
				), 'id,title,element,required,is_show,alias', 'sort');
				
				$user['props'] = $this->getProps($user['id'], $props);
			}
		}
		
		//删除不需要返回的字段
		$return = array();
		foreach($ids as $id){
			foreach($users as $u){
				if($id == $u['id']){
					if(!in_array('id', $fields['users'])){
						unset($u['id']);
					}
					if(!in_array('role', $fields['users'])){
						unset($u['role']);
					}
					$return[$id] = $u;
				}
			}
		}
		return $return;
	}
	
	/**
	 * 获取用户附加属性<br>
	 * 可传入props（并不一定真的是当前用户分类对应的属性，比如编辑用户所属分类的时候会传入其他属性）<br>
	 * 若不传入，则会自动获取当前用户所属角色的属性集
	 */
	public function getProps($user_id, $props = null){
		if($props === null){
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
* @param mixed $value
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