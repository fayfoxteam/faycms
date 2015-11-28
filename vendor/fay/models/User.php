<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Users;
use fay\models\tables\Roles;
use fay\models\tables\Props;
use fay\helpers\Request;
use fay\models\tables\RolesCats;
use fay\helpers\SqlHelper;
use fay\models\tables\UsersRoles;
use fay\helpers\ArrayHelper;
use fay\core\Sql;
use fay\models\tables\UserProfile;
use fay\core\db\Expr;
use fay\core\Hook;
use fay\helpers\String;

class User extends Model{
	/**
	 * @param string $className
	 * @return User
	 */
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 用户登录
	 * @param string $username 用户名
	 * @param string $password 密码
	 * @param string $admin 若为true，则限定为管理员登录（管理员也可以登录前台，但前后台的Session空间是分开的）
	 */
	public function login($username, $password, $admin = false){
		if(!$username){
			return array(
				'status'=>0,
				'message'=>'用户名不能为空',
				'error_code'=>'username:can-not-be-empty',
			);
		}
		if(!$password){
			return array(
				'status'=>0,
				'message'=>'密码不能为空',
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
				'message'=>'用户名不存在',
				'error_code'=>'username:not-exist',
			);
		}
		$password = md5(md5($password).$user['salt']);
		if($password != $user['password']){
			return array(
				'status'=>0,
				'message'=>'密码错误',
				'error_code'=>'password:not-match',
			);
		}
		
		if($user['block']){
			return array(
				'status'=>0,
				'message'=>'用户已锁定',
				'error_code'=>'block:blocked',
			);
		}
		
		if($user['status'] == Users::STATUS_UNCOMPLETED){
			return array(
				'status'=>0,
				'message'=>'账号信息不完整',
				'error_code'=>'status:uncompleted',
			);
		}else if($user['status'] == Users::STATUS_PENDING){
			return array(
				'status'=>0,
				'message'=>'账号正在审核中',
				'error_code'=>'status:pending',
			);
		}else if($user['status'] == Users::STATUS_VERIFY_FAILED){
			return array(
				'status'=>0,
				'message'=>'账号未通过审核',
				'error_code'=>'status:verify-failed',
			);
		}else if($user['status'] == Users::STATUS_NOT_VERIFIED){
			return array(
				'status'=>0,
				'message'=>'请先验证邮箱',
				'error_code'=>'status:not-verified',
			);
		}
		
		if($admin && $user['admin'] != $admin){
			return array(
				'status'=>0,
				'message'=>'您不是管理员，不能登陆！',
				'error_code'=>'not-admin',
			);
		}
		
		$user['roles'] = $this->getRoleIds($user['id']);
		$user_profile = UserProfile::model()->find($user['id']);
		$user = $user + $user_profile;
		
		$this->setSessionInfo($user);
		
		//设置权限，超级管理员无需设置
		if(!in_array(Roles::ITEM_SUPER_ADMIN, $user['roles'])){
			$sql = new Sql();
			$actions = $sql->from('roles_actions', 'ra', '')
				->joinLeft('actions', 'a', 'ra.action_id = a.id', 'router')
				->where('ra.role_id IN ('.implode(',', $user['roles']).')')
				->group('a.router')
				->fetchAll();
			\F::session()->set('actions', ArrayHelper::column($actions, 'router'));
				
			//分类权限
			if(Option::get('system:role_cats')){
				//未分类文章任何人都有权限编辑
				$post_root = Category::model()->get('_system_post', 'id');
				\F::session()->set('role_cats', array_merge(array(0, $post_root['id']), RolesCats::model()->fetchCol('cat_id', 'role_id IN ('.implode(',', $user['roles']).')')));
			}
		}
		
		UserProfile::model()->update(array(
			'last_login_ip'=>Request::ip2int(\F::app()->ip),
			'last_login_time'=>\F::app()->current_time,
			'last_time_online'=>\F::app()->current_time,
			'login_times'=>new Expr('login_times + 1'),
		), $user['id']);
		
		Hook::getInstance()->call('after_login', array(
			'user'=>$user,
		));
		
		return array(
			'status'=>1,
			'user'=>$user,
		);
	}
	
	public function setSessionInfo($user){
		\F::session()->set('user', array(
			'id'=>$user['id'],
			'username'=>$user['username'],
			'nickname'=>$user['nickname'],
			'avatar'=>$user['avatar'],
			'roles'=>$user['roles'],
			'last_login_time'=>$user['last_login_time'],
			'last_login_ip'=>long2ip($user['last_login_ip']),
			'status'=>$user['status'],
			'admin'=>$user['admin'],
		));
	}
	
	public function logout(){
		\F::session()->remove();
	}
	
	/**
	 * 返回单个用户
	 * @param string|array $id 用户id
	 * @param string $fields 可指定返回字段
	 *  - users.*系列可指定users表返回字段，若有一项为'users.*'，则返回除密码字段外的所有字段
	 *  - roles.*系列可指定返回哪些角色字段，若有一项为'roles.*'，则返回所有角色字段
	 *  - props.*系列可指定返回哪些角色属性，若有一项为'props.*'，则返回所有角色属性
	 *  - profile.*系列可指定返回哪些用户资料，若有一项为'profile.*'，则返回所有用户资料
	 * @return false|array 若用户ID不存在，返回false，否则返回数组
	 */
	public function get($id, $fields = 'users.username,users.nickname,users.id,users.avatar'){
		//解析$fields
		$fields = SqlHelper::processFields($fields, 'users');
		if(empty($fields['users'])){
			//若未指定返回字段，初始化
			$fields['users'] = array(
				'id', 'username', 'nickname',
			);
		}else if(in_array('*', $fields['users'])){
			//若存在*，视为全字段搜索，但密码字段不会被返回
			$fields['users'] = Users::model()->getFields('password,salt');
		}else{
			//永远不会返回密码字段
			foreach($fields['users'] as $k => $v){
				if($v == 'password' || $v == 'salt'){
					unset($fields['users'][$k]);
				}
			}
		}
		
		$user = Users::model()->find($id, implode(',', $fields['users']));
		
		if(!$user){
			return false;
		}
		
		if(isset($user['avatar'])){
			//如果有头像，将头像转为图片URL
			$user['avatar'] = File::getUrl($user['avatar'], File::PIC_ORIGINAL, array(
				'spare'=>'avatar',
			));
		}
		
		$return['user'] = $user;
		if(!empty($fields['props'])){
			$user_roles = $this->getRoleIds($id);
			if($user_roles){
				//附加角色属性
				$props = Props::model()->fetchAll(array(
					'refer IN ('.implode(',', $user_roles).')',
					'type = '.Props::TYPE_ROLE,
					'deleted = 0',
					'alias IN (?)'=>in_array('*', $fields['props']) ? false : $fields['props'],
				), 'id,title,element,required,is_show,alias', 'sort');
				
				$return['props'] = $this->getProps($id, $props);
			}else{
				$return['props'] = array();
			}
		}
		
		if(!empty($fields['roles'])){
			$return['roles'] = $this->getRoles($id, $fields['roles']);
		}
		
		if(!empty($fields['profile'])){
			$return['profile'] = UserProfile::model()->find($id, implode(',', $fields['profile']));
		}
		
		return $return;
	}
	
	/**
	 * 返回多个用户
	 * @param string|array $ids 可以是逗号分割的id串，也可以是用户ID构成的一维数组
	 * @param string $fields 可指定返回字段
	 *   users.*系列可指定users表返回字段，若有一项为'users.*'，则返回除密码字段外的所有字段
	 *   roles.*系列可指定返回哪些角色字段，若有一项为'roles.*'，则返回所有角色字段
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
				'id', 'username', 'nickname', 'avatar',
			);
		}else if(in_array('*', $fields['users'])){
			//若存在*，视为全字段搜索，但密码字段不会被返回
			$fields['users'] = Users::model()->getFields('password,salt');
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
		), implode(',', empty($fields['props']) ? $fields['users'] : array_merge($fields['users'], array('id'))));
		
		//根据传入id顺序排序，并删除不需要返回的字段
		$return = array();
		foreach($ids as $id){
			foreach($users as $user){
				if($id == $user['id']){
					if(!empty($fields['roles'])){
						//附加用户角色
						$user['roles'] = $this->getRoles($user['id'], in_array('*', $fields['roles']) ? '*' : $fields['roles']);
					}
						
					if(!empty($fields['props'])){
						//附加用户角色属性
						$user_roles = $this->getRoleIds($user['id']);
						$props = Props::model()->fetchAll(array(
							'refer IN ('.implode(',', $user_roles).')',
							'type = '.Props::TYPE_ROLE,
							'deleted = 0',
							'alias IN (?)'=>in_array('*', $fields['props']) ? false : $fields['props'],
						), 'id,title,element,required,is_show,alias', 'sort');
							
						$user['props'] = $this->getProps($user['id'], $props);
					}
					
					if(!in_array('id', $fields['users'])){
						unset($user['id']);
					}
					$return[$id] = $user;
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
	public function getPropertySet($user_id, $props = null){
		if($props === null){
			$props = $this->getProps($user_id);
		}
		
		return Prop::model()->getPropertySet('user_id', $user_id, $props, array(
			'varchar'=>'fay\models\tables\UserPropVarchar',
			'int'=>'fay\models\tables\UserPropInt',
			'text'=>'fay\models\tables\UserPropText',
		));
	}
	
	/**
	 * 根据用户ID，获取用户对应属性（不带属性值）
	 * @param int $user_id
	 */
	public function getProps($user_id){
		$role_ids = User::model()->getRoleIds($user_id);
		return $this->getPropsByRoles($role_ids);
	}
	
	/**
	 * 根据角色id，获取相关属性（不带属性值）
	 * @param array $role_ids 由角色id构成的一维数组
	 */
	public function getPropsByRoles($role_ids){
		return Prop::model()->mget($role_ids, Props::TYPE_ROLE);
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
			'varchar'=>'fay\models\tables\UserPropVarchar',
			'int'=>'fay\models\tables\UserPropInt',
			'text'=>'fay\models\tables\UserPropText',
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
			'varchar'=>'fay\models\tables\UserPropVarchar',
			'int'=>'fay\models\tables\UserPropInt',
			'text'=>'fay\models\tables\UserPropText',
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
	
	/**
	 * 获取用户角色ID（一维数组）若未登陆，返回空数组
	 * @param int|null $user
	 */
	public function getRoleIds($user_id = null){
		if(!$user_id && isset(\F::app()->current_user)){
			$user_id = \F::app()->current_user;
		}
		if(!$user_id){
			return array();
		}
		
		$user_roles = UsersRoles::model()->fetchAll('user_id = ' . $user_id, 'role_id');
		return ArrayHelper::column($user_roles, 'role_id');
	}
	
	/**
	 * 获取用户角色详细（若未登陆，返回空数组）
	 * @param int|null $user_id
	 * @param string $fields 角色字段（roles表字段）
	 */
	public function getRoles($user_id = null, $fields = '*'){
		if(!$user_id && isset(\F::app()->current_user)){
			$user_id = \F::app()->current_user;
		}
		if(!$user_id){
			return array();
		}
		
		$sql = new Sql();
		return $sql->from('users_roles', 'ur', '')
			->joinLeft('roles', 'r', 'ur.role_id = r.id', $fields)
			->where('ur.user_id = '.$user_id)
			->fetchAll();
	}
	
	/**
	 * 判断一个用户ID是否存在，若为0或者其他等价于false的值，直接返回false
	 * @param int $user_id
	 */
	public static function isUserIdExist($user_id){
		if($user_id){
			return !!Users::model()->find($user_id, 'id');
		}else{
			return false;
		}
	}
	
	/**
	 * 新增一个用户属性集
	 * @param int $user_id 用户ID
	 * @param array $data 以属性ID为键的属性键值数组
	 * @param null|array $props 属性。若为null，则根据用户ID获取属性
	 */
	public function createPropertySet($user_id, $data, $props = null){
		if($props === null){
			$props = $this->getProps($user_id);
		}
		Prop::model()->createPropertySet('user_id', $user_id, $props, $data, array(
			'varchar'=>'fay\models\tables\UserPropVarchar',
			'int'=>'fay\models\tables\UserPropInt',
			'text'=>'fay\models\tables\UserPropText',
		));
	}
	
	/**
	 * 更新一个用户属性集
	 * @param int $user_id 用户ID
	 * @param array $data 以属性ID为键的属性键值数组
	 * @param null|array $props 属性。若为null，则根据用户ID获取属性
	 */
	public function updatePropertySet($user_id, $data, $props = null){
		if($props === null){
			$props = $this->getProps($user_id);
		}
		Prop::model()->updatePropertySet('user_id', $user_id, $props, $data, array(
			'varchar'=>'fay\models\tables\UserPropVarchar',
			'int'=>'fay\models\tables\UserPropInt',
			'text'=>'fay\models\tables\UserPropText',
		));
	}
	
	/**
	 * 创建一个用户
	 * @param array $user
	 * @param array $extra 其它信息
	 *  - roles 角色ID，逗号分隔或一维数组
	 *  - props 以属性ID为键，属性值为值构成的关联数组
	 *  - trackid 字符串，用于追踪用户来源的自定义标识码
	 */
	public function create($user, $extra = array(), $is_admin = 0){
		if(!empty($user['password'])){
			$auth_key = $this->generatePassword($user['password']);
			$user['salt'] = $auth_key['salt'];
			$user['password'] = $auth_key['password'];
		}
		
		//过滤掉多余的数据
		$user = Users::model()->fillData($user, false);
		//插用户表
		$user_id = Users::model()->insert($user);
		//插用户扩展表
		UserProfile::model()->insert(array(
			'user_id'=>$user_id,
			'reg_time'=>\F::app()->current_time,
			'reg_ip'=>Request::ip2int(Request::getIP()),
			'trackid'=>isset($extra['trackid']) ? $extra['trackid'] : '',
		));
		
		//插角色表
		if(!empty($extra['roles'])){
			if(!is_array($extra['roles'])){
				$extra['roles'] = explode(',', $extra['roles']);
			}
			$user_roles = array();
			foreach($extra['roles'] as $r){
				$user_roles[] = array(
					'user_id'=>$user_id,
					'role_id'=>$r,
				);
			}
			UsersRoles::model()->bulkInsert($user_roles);
			
		}
		
		//设置属性
		if($extra['props']){
			$this->createPropertySet($user_id, $extra['props']);
		}
		
		return $user_id;
	}
	
	/**
	 * 更新一个用户
	 * @param array $user
	 * @param array $extra 其它信息
	 *  - roles 角色ID，逗号分隔或一维数组
	 *  - props 以属性ID为键，属性值为值构成的关联数组
	 *  - trackid 字符串，用于追踪用户来源的自定义标识码
	 */
	public function update($user_id, $user, $extra = array()){
		if(isset($user['password'])){
			if($user['password']){
				//非空，则更新密码字段
				$auth_key = $this->generatePassword($user['password']);
				$user['salt'] = $auth_key['salt'];
				$user['password'] = $auth_key['password'];
			}else{
				//为空，则不更新密码字段
				unset($user['password'], $user['salt']);
			}
		}
		
		//过滤掉多余的数据
		$user = Users::model()->fillData($user, false);
		
		if($user){
			//更新用户表（也有可能没数据提交不更新）
			Users::model()->update($user, $user_id);
		}
		
		if(isset($extra['roles'])){
			if(!is_array($extra['roles'])){
				$extra['roles'] = explode(',', $extra['roles']);
			}
			if(!empty($extra['roles'])){
				//删除被删除了的角色
				UsersRoles::model()->delete(array(
					'user_id = ?'=>$user_id,
					'role_id NOT IN (?)'=>$extra['roles'],
				));
				$user_roles = array();
				foreach($extra['roles'] as $r){
					if(!UsersRoles::model()->fetchRow(array(
						'user_id = ?'=>$user_id,
						'role_id = ?'=>$r,
					))){
						//不存在，则插入
						$user_roles[] = array(
							'user_id'=>$user_id,
							'role_id'=>$r,
						);
					}
				}
				UsersRoles::model()->bulkInsert($user_roles);
			}else{
				//删除全部角色
				UsersRoles::model()->delete(array(
					'user_id = ?'=>$user_id,
				));
			}
		}
		
		//附加属性
		if(isset($extra['props'])){
			$this->updatePropertySet($user_id, $extra['props']);
		}
	}
	
	/**
	 * 根据明码，得到一个加密后的密码和混淆码
	 */
	public function generatePassword($password){
		$salt = String::random('alnum', 5);
		return array(
			'salt'=>$salt,
			'password'=>md5(md5($password) . $salt),
		);
	}
}