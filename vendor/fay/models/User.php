<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Users;
use fay\models\tables\Roles;
use fay\models\tables\Props;
use fay\helpers\Request;
use fay\models\tables\RolesCats;
use fay\helpers\FieldHelper;
use fay\models\tables\UsersRoles;
use fay\helpers\ArrayHelper;
use fay\core\Sql;
use fay\models\tables\UserProfile;
use fay\core\db\Expr;
use fay\core\Hook;
use fay\models\user\Profile;
use fay\models\user\Role;
use fay\models\user\Password;
use fay\models\tables\UserCounter;

class User extends Model{
	/**
	 * 可选字段
	*/
	public static $public_fields = array(
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
		'roles'=>array(
			'id', 'title',
		),
	);
	
	/**
	 * 默认返回用户字段
	 */
	public static $default_fields = array(
		'user'=>array(
			'id', 'nickname', 'avatar',
		)
	);
	
	/**
	 * @return User
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
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
		), 'id,password,salt,block,status,admin');
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
		
		//重新获取用户信息，这次获取更全面的信息
		$user = $this->get($user['id'], array(
			'user'=>array('id', 'username', 'nickname', 'avatar', 'status', 'admin'),
			'profile'=>array('last_login_time', 'last_login_ip'),
			'roles'=>'id',
		));
		$this->setSessionInfo($user);
		
		$role_ids = ArrayHelper::column($user['roles'], 'id');
		//设置权限，超级管理员无需设置
		if(!in_array(Roles::ITEM_SUPER_ADMIN, $role_ids)){
			if($role_ids){
				$sql = new Sql();
				$actions = $sql->from(array('ra'=>'roles_actions'), '')
					->joinLeft(array('a'=>'actions'), 'ra.action_id = a.id', 'router')
					->where('ra.role_id IN ('.implode(',', $role_ids).')')
					->group('a.router')
					->fetchAll();
				\F::session()->set('actions', ArrayHelper::column($actions, 'router'));
					
				//分类权限
				if(Option::get('system:post_role_cats')){
					//未分类文章任何人都有权限编辑
					$post_root = Category::model()->get('_system_post', 'id');
					\F::session()->set('role_cats', array_merge(array(0, $post_root['id']), RolesCats::model()->fetchCol('cat_id', 'role_id IN ('.implode(',', $role_ids).')')));
				}
			}else{
				//用户不属于任何角色，则角色权限为空
				\F::session()->set('actions', array());
			}
		}
		
		UserProfile::model()->update(array(
			'last_login_ip'=>Request::ip2int(\F::app()->ip),
			'last_login_time'=>\F::app()->current_time,
			'last_time_online'=>\F::app()->current_time,
			'login_times'=>new Expr('login_times + 1'),
		), $user['user']['id']);
		
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
			'id'=>$user['user']['id'],
			'username'=>$user['user']['username'],
			'nickname'=>$user['user']['nickname'],
			'avatar'=>$user['user']['avatar'],
			'roles'=>ArrayHelper::column($user['roles'], 'id'),
			'status'=>$user['user']['status'],
			'admin'=>$user['user']['admin'],
			'last_login_time'=>$user['profile']['last_login_time'],
			'last_login_ip'=>long2ip($user['profile']['last_login_ip']),
		));
	}
	
	public function logout(){
		\F::session()->remove();
	}
	
	/**
	 * 返回单个用户
	 * @param string|array $id 用户id
	 * @param string $fields 可指定返回字段
	 *  - user.*系列可指定users表返回字段，若有一项为'user.*'，则返回除密码字段外的所有字段
	 *  - roles.*系列可指定返回哪些角色字段，若有一项为'roles.*'，则返回所有角色字段
	 *  - props.*系列可指定返回哪些角色属性，若有一项为'props.*'，则返回所有角色属性
	 *  - profile.*系列可指定返回哪些用户资料，若有一项为'profile.*'，则返回所有用户资料
	 * @return false|array 若用户ID不存在，返回false，否则返回数组
	 */
	public function get($id, $fields = 'user.username,user.nickname,user.id,user.avatar'){
		//解析$fields
		$fields = FieldHelper::process($fields, 'user');
		if(empty($fields['user'])){
			//若未指定返回字段，初始化
			$fields['user'] = array(
				'id', 'username', 'nickname', 'avatar',
			);
		}else if(in_array('*', $fields['user'])){
			//若存在*，视为全字段搜索，但密码字段不会被返回
			$fields['user'] = Users::model()->getFields('password,salt');
		}else{
			//永远不会返回密码字段
			foreach($fields['user'] as $k => $v){
				if($v == 'password' || $v == 'salt'){
					unset($fields['user'][$k]);
				}
			}
		}
		
		$user = Users::model()->find($id, implode(',', $fields['user']));
		
		if(!$user){
			return false;
		}
		
		if(isset($user['avatar'])){
			//如果有头像，将头像转为图片URL
			$user['avatar_url'] = File::getUrl($user['avatar'], File::PIC_ORIGINAL, array(
				'spare'=>'avatar',
			));
		}
		
		$return['user'] = $user;
		//角色属性
		if(!empty($fields['props'])){
			$return['props'] = $this->getPropertySet($id, in_array('*', $fields['props']) ? null : $fields['props']);
		}
		
		//角色
		if(!empty($fields['roles'])){
			$return['roles'] = Role::model()->get($id, $fields['roles']);
		}
		
		//profile
		if(!empty($fields['profile'])){
			$return['profile'] = Profile::model()->get($id, $fields['profile']);
		}
		
		return $return;
	}
	
	/**
	 * 返回多个用户
	 * @param string|array $ids 可以是逗号分割的id串，也可以是用户ID构成的一维数组
	 * @param string $fields 可指定返回字段
	 *  - user.*系列可指定users表返回字段，若有一项为'user.*'，则返回除密码字段外的所有字段
	 *  - roles.*系列可指定返回哪些角色字段，若有一项为'roles.*'，则返回所有角色字段
	 *  - props.*系列可指定返回哪些角色属性，若有一项为'props.*'，则返回所有角色属性（星号指代的是角色属性的别名）
	 *  - profile.*系列可指定返回哪些用户资料，若有一项为'profile.*'，则返回所有用户资料
	 */
	public function mget($ids, $fields = 'user.username,user.nickname,user.id,user.avatar'){
		if(empty($ids)){
			return array();
		}
		
		//解析$ids
		is_array($ids) || $ids = explode(',', $ids);
		
		//解析$fields
		$fields = FieldHelper::process($fields, 'user');
		if(empty($fields['user'])){
			//若未指定返回字段，初始化
			$fields['user'] = array(
				'id', 'username', 'nickname', 'avatar',
			);
		}else if(in_array('*', $fields['user'])){
			//若存在*，视为全字段搜索，但密码字段不会被返回
			$fields['user'] = Users::model()->getFields('password,salt');
		}else{
			//永远不会返回密码字段
			foreach($fields['user'] as $k => $v){
				if($v == 'password' || $v == 'salt'){
					unset($fields['user'][$k]);
				}
			}
		}
		
		$remove_id_field = false;
		if(!in_array('id', $fields['user'])){
			//id总是需要先搜出来的，返回的时候要作为索引
			$fields['user'][] = 'id';
			$remove_id_field = true;
		}
		$users = Users::model()->fetchAll(array(
			'id IN (?)'=>$ids,
		), $fields['user']);
		
		if(!empty($fields['profile'])){
			//获取所有相关的profile
			$profiles = Profile::model()->mget($ids, $fields['profile']);
		}
		if(!empty($fields['roles'])){
			//获取所有相关的roles
			$roles = Role::model()->mget($ids, $fields['roles']);
		}
		
		$return = array_fill_keys($ids, array());
		foreach($users as $u){
			$user['user'] = $u;
			if(isset($user['user']['avatar'])){
				//如果有头像，将头像转为图片URL
				$user['user']['avatar_url'] = File::getUrl($user['user']['avatar'], File::PIC_ORIGINAL, array(
					'spare'=>'avatar',
				));
			}
			
			//profile
			if(!empty($fields['profile'])){
				$user['profile'] = $profiles[$u['id']];
			}
			
			//角色
			if(!empty($fields['roles'])){
				$user['roles'] = $roles[$u['id']];
			}
			
			//角色属性
			if(!empty($fields['props'])){
				$user['props'] = $this->getPropertySet($u['id'], in_array('*', $fields['props']) ? null : $fields['props']);
			}
			
			if($remove_id_field){
				//移除id字段
				unset($user['user']['id']);
			}
			
			$return[$u['id']] = $user;
		}
		
		return $return;
	}
	
	/**
	 * 获取用户附加属性
	 * 可传入props（并不一定真的是当前用户分类对应的属性，比如编辑用户所属分类的时候会传入其他属性）<br>
	 * 若不传入，则会自动获取当前用户所属角色的属性集
	 */
	public function getPropertySet($user_id, $props = null){
		if($props === null){
			$props = $this->getProps($user_id);
		}else{
			$props = Prop::model()->mgetByAlias($props, Props::TYPE_ROLE);
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
		$role_ids = Role::model()->getIds($user_id);
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
	 * 判断一个用户ID是否存在，若为0或者其他等价于false的值，直接返回false。
	 * 即便是deleted标记为已删除的用户，也被视为存着的用户ID
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
			$auth_key = Password::model()->generate($user['password']);
			$user['salt'] = $auth_key['salt'];
			$user['password'] = $auth_key['password'];
		}
		
		//过滤掉多余的数据
		$user = Users::model()->fillData($user, false);
		$user['admin'] = $is_admin;
		//插用户表
		$user_id = Users::model()->insert($user);
		//插用户扩展表
		UserProfile::model()->insert(array(
			'user_id'=>$user_id,
			'reg_time'=>\F::app()->current_time,
			'reg_ip'=>Request::ip2int(Request::getIP()),
			'trackid'=>isset($extra['trackid']) ? $extra['trackid'] : '',
		));
		//插入用户计数表
		UserCounter::model()->insert(array(
			'user_id'=>$user_id,
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
				$auth_key = Password::model()->generate($user['password']);
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
}