<?php
namespace fay\services;

use fay\core\Service;
use fay\helpers\FieldHelper;
use fay\helpers\Request;
use fay\core\db\Expr;
use fay\models\tables\Actions;
use fay\models\tables\Roles;
use fay\models\tables\UserProfile;
use fay\models\tables\Users;
use fay\models\tables\UsersRoles;
use fay\services\user\Counter;
use fay\services\user\Password;
use fay\models\tables\UserCounter;
use fay\services\user\Profile;
use fay\services\user\Prop;
use fay\services\user\Exception as UserException;
use fay\models\tables\UserLogins;
use fay\services\user\Role;

/**
 * 用户服务
 */
class UserService extends Service{
	
	const EVENT_LOGIN = 'after_login';
	
	/**
	 * 公开字段（接口访问时，可以此判断是否可返回字段，服务自身不做判断）
	 */
	public static $public_fields = array(
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
		'roles'=>array(
			'id', 'title',
		),
		'props'=>array(
			'*',
		),
		'counter'=>array(
			'*',
		),
		'profile'=>array(
			'reg_time', 'reg_ip'
		)
	);
	
	/**
	 * 默认返回用户字段
	 */
	public static $default_fields = array(
		'user'=>array(
			'fields'=>array(
				'id', 'nickname', 'avatar',
			)
		)
	);
	
	/**
	 * 以用户为单位，缓存经检查允许的路由
	 */
	private $_allowed_routers = array();
	
	/**
	 * 以用户为单位，缓存经检查不允许的路由
	 */
	private $_denied_routers = array();
	
	/**
	 * @param string $class_name
	 * @return UserService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 用户登录（直接登陆指定用户ID，不做任何验证）
	 * @param int $user_id 用户ID
	 * @return array
	 */
	public function login($user_id){
		//获取用户信息
		$user = $this->get($user_id, array(
			'user'=>array(
				'fields'=>array('id', 'username', 'nickname', 'avatar', 'admin')
			),
			'profile'=>array(
				'fields'=>array('last_login_time', 'last_login_ip')
			),
			'roles'=>array(
				'fields'=>array('id')
			),
		));
		
		if(!$user){
			return false;
		}
		
		//设置Session
		\F::session()->set('user', array(
			'id'=>$user['user']['id'],
		));
		
		//更新用户最后登录信息
		UserProfile::model()->update(array(
			'last_login_ip'=>Request::ip2int(\F::app()->ip),
			'last_login_time'=>\F::app()->current_time,
			'last_time_online'=>\F::app()->current_time,
			'login_times'=>new Expr('login_times + 1'),
		), $user['user']['id']);
		
		//记录登录日志
		UserLogins::model()->insert(array(
			'user_id'=>$user['user']['id'],
			'login_time'=>\F::app()->current_time,
			'ip_int'=>Request::ip2int(\F::app()->ip),
			'mac'=>AnalystService::service()->getMacId(),
		));
		
		\F::event()->trigger(self::EVENT_LOGIN, array(
			'user'=>$user,
		));
		
		return array(
			'user'=>$user,
		);
	}
	
	/**
	 * 退出登录，销毁session
	 */
	public function logout(){
		\F::session()->remove();
	}
	
	/**
	 * 创建一个用户
	 * @param array $user
	 * @param array $extra 其它信息
	 *  - roles 角色ID，逗号分隔或一维数组
	 *  - props 以属性ID为键，属性值为值构成的关联数组
	 *  - trackid 字符串，用于追踪用户来源的自定义标识码
	 * @param int $is_admin
	 * @return int|null
	 * @throws UserException
	 */
	public function create($user, $extra = array(), $is_admin = 0){
		if(!empty($user['password'])){
			list($user['salt'], $user['password']) = PasswordService::service()->generate($user['password']);
		}
		
		//过滤掉多余的数据
		$user = Users::model()->fillData($user, false, 'insert');
		$user['admin'] = $is_admin ? 1 : 0;
		
		//信息验证（用户信息很重要，在入库前必须再做一次验证）
		$config = Option::mget(array(
			'system:user_nickname_required',
			'system:user_nickname_unique'
		));
		if(!isset($user['username']) || $user['username'] == ''){
			throw new UserException('用户名不能为空', 'missing-parameter:username');
		}
		if($config['system:user_nickname_required'] && !isset($user['nickname']) || $user['nickname'] == ''){
			throw new UserException('用户昵称不能为空', 'missing-parameter:nickname');
		}
		
		if(Users::model()->fetchRow(array(
			'username = ?'=>$user['username'],
		))){
			throw new UserException('用户名已存在', 'invalid-parameter:username-is-exist');
		}
		
		if($config['system:user_nickname_unique'] && Users::model()->fetchRow(array(
			'nickname = ?'=>$user['nickname'],
		))){
			throw new UserException('用户昵称已存在', 'invalid-parameter:username-is-exist');
		}
		
		//插用户表
		$user_id = Users::model()->insert($user);
		
		//插用户扩展表
		$user_profile = array(
			'user_id'=>$user_id,
			'reg_time'=>\F::app()->current_time,
			'reg_ip'=>Request::ip2int(Request::getIP()),
		);
		if(isset($extra['profile'])){
			$user_profile = $user_profile + $extra['profile'];
		}
		UserProfile::model()->insert($user_profile, true);
		
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
		if(isset($extra['props'])){
			PropService::service()->createPropertySet($user_id, $extra['props']);
		}
		
		return $user_id;
	}
	
	/**
	 * 更新一个用户
	 * @param $user_id
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
				list($user['salt'], $user['password']) = PasswordService::service()->generate($user['password']);
			}else{
				//为空，则不更新密码字段
				unset($user['password'], $user['salt']);
			}
		}
		
		//过滤掉多余的数据
		Users::model()->update($user, $user_id, true);
		
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
			
			//删除角色相关缓存
			\F::cache()->delete("user.actions.{$user_id}");
			\F::cache()->delete("user.role_ids.{$user_id}");
		}
		
		if(isset($extra['profile'])){
			UserProfile::model()->update($extra['profile'], $user_id, true);
		}
		
		//附加属性
		if(isset($extra['props'])){
			PropService::service()->updatePropertySet($user_id, $extra['props']);
		}
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
		$fields = FieldHelper::parse($fields, 'user');
		if(empty($fields['user'])){
			//若未指定返回字段，初始化
			$fields['user'] = array(
				'fields'=>array(
					'id', 'username', 'nickname', 'avatar',
				)
			);
		}else if(in_array('*', $fields['user']['fields'])){
			//若存在*，视为全字段搜索，但密码字段不会被返回
			$fields['user']['fields'] = Users::model()->getFields(array('password', 'salt'));
		}else{
			//永远不会返回密码字段
			foreach($fields['user']['fields'] as $k => $v){
				if($v == 'password' || $v == 'salt'){
					unset($fields['user']['fields'][$k]);
				}
			}
		}
		
		if(empty($fields['user']['fields'])){
			$user = array();
		}else{
			$user = Users::model()->find($id, $fields['user']['fields']);
		}
		
		if($user === false){
			return false;
		}
		
		if(isset($user['avatar'])){
			//如果有头像，将头像图片ID转化为图片对象
			if(isset($fields['user']['extra']['avatar']) && preg_match('/^(\d+)x(\d+)$/', $fields['user']['extra']['avatar'], $avatar_params)){
				$user['avatar'] = FileService::get($user['avatar'], array(
					'spare'=>'avatar',
					'dw'=>$avatar_params[1],
					'dh'=>$avatar_params[2],
				));
			}else{
				$user['avatar'] = FileService::get($user['avatar'], array(
					'spare'=>'avatar',
				));
			}
		}
		
		if($user){
			$return['user'] = $user;
		}else{
			$return = array();
		}
		
		//角色属性
		if(!empty($fields['props'])){
			if(in_array('*', $fields['props']['fields'])){
				$props = null;
			}else{
				$props = PropService::service()->mget($fields['props']);
			}
			$return['props'] = PropService::service()->getPropertySet($id, $props);
		}
		
		//角色
		if(!empty($fields['roles'])){
			$return['roles'] = RoleService::service()->get($id, $fields['roles']);
		}
		
		//profile
		if(!empty($fields['profile'])){
			$return['profile'] = ProfileService::service()->get($id, $fields['profile']);
		}
		
		//counter
		if(!empty($fields['counter'])){
			$return['counter'] = CounterService::service()->get($id, $fields['counter']);
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
	 *  - counter.*系列可指定返回哪些用户计数器，若有一项为'counter.*'，则返回所有计数字段
	 * @param array $extra 扩展信息。例如：头像缩略图尺寸
	 * @return array
	 */
	public function mget($ids, $fields = 'user.username,user.nickname,user.id,user.avatar', $extra = array()){
		if(empty($ids)){
			return array();
		}
		
		//解析$ids
		is_array($ids) || $ids = explode(',', $ids);
		
		//解析$fields
		$fields = FieldHelper::parse($fields, 'user');
		if(empty($fields['user'])){
			//若未指定返回字段，初始化
			$fields['user'] = array(
				'fields'=>array(
					'id', 'username', 'nickname', 'avatar',
				)
			);
		}else if(in_array('*', $fields['user'])){
			//若存在*，视为全字段搜索，但密码字段不会被返回
			$fields['user'] = array(
				'fields'=>Users::model()->getFields(array('password', 'salt'))
			);
		}else{
			//永远不会返回密码字段
			foreach($fields['user'] as $k => $v){
				if($v == 'password' || $v == 'salt'){
					unset($fields['user'][$k]);
				}
			}
		}
		
		$remove_id_field = false;
		if(empty($fields['user']['fields']) || !in_array('id', $fields['user']['fields'])){
			//id总是需要先搜出来的，返回的时候要作为索引
			$fields['user']['fields'][] = 'id';
			$remove_id_field = true;
		}
		$users = Users::model()->fetchAll(array(
			'id IN (?)'=>$ids,
		), $fields['user']['fields']);
		
		if(!empty($fields['profile'])){
			//获取所有相关的profile
			$profiles = ProfileService::service()->mget($ids, $fields['profile']);
		}
		if(!empty($fields['roles'])){
			//获取所有相关的roles
			$roles = RoleService::service()->mget($ids, $fields['roles']);
		}
		if(!empty($fields['counter'])){
			$counters = CounterService::service()->mget($ids, $fields['counter']);
		}
		
		$return = array_fill_keys($ids, array());
		foreach($users as $u){
			$user['user'] = $u;
			if(isset($user['user']['avatar'])){
				//如果有头像，将头像图片ID转化为图片对象
				if(isset($extra['user']['avatar']) && preg_match('/^(\d+)x(\d+)$/', $extra['user']['avatar'], $avatar_params)){
					$user['user']['avatar'] = FileService::get($u['avatar'], array(
						'spare'=>'avatar',
						'dw'=>$avatar_params[1],
						'dh'=>$avatar_params[2],
					));
				}else{
					$user['user']['avatar'] = FileService::get($u['avatar'], array(
						'spare'=>'avatar',
					));
				}
			}
			
			//profile
			if(isset($profiles)){
				$user['profile'] = $profiles[$u['id']];
			}
			
			//角色
			if(isset($roles)){
				$user['roles'] = $roles[$u['id']];
			}
			
			//角色
			if(isset($counters)){
				$user['counter'] = $counters[$u['id']];
			}
			
			//角色属性
			if(!empty($fields['props'])){
				if(in_array('*', $fields['props'])){
					$props = null;
				}else{
					$props = PropService::service()->mget($fields['props']);
				}
				$user['props'] = PropService::service()->getPropertySet($u['id'], $props);
			}
			
			if($remove_id_field){
				//移除id字段
				unset($user['user']['id']);
				if(empty($user['user'])){
					unset($user['user']);
				}
			}
			
			$return[$u['id']] = $user;
		}
		
		return $return;
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
	 * @return bool
	 */
	public static function isUserIdExist($user_id){
		if($user_id){
			return !!Users::model()->find($user_id, 'id');
		}else{
			return false;
		}
	}
	
	/**
	 * 根据路由做权限检查
	 * 从数据库中获取role.id和actions信息
	 * @param string $router 路由
	 * @param int $user_id 用户ID，若为空，则默认为当前登录用户
	 * @return bool
	 */
	public function checkPermission($router, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		
		//已经检查过是允许的路由，直接返回true
		if(isset($this->_allowed_routers[$user_id]) &&
			in_array($router, $this->_allowed_routers[$user_id])){
			return true;
		}
		
		//已经检查过是不允许的路由，直接返回false
		if(isset($this->_denied_routers[$user_id]) &&
			in_array($router, $this->_denied_routers[$user_id])){
			return false;
		}
		
		$roles = RoleService::service()->getIds($user_id);
		if(in_array(Roles::ITEM_SUPER_ADMIN, $roles)){
			//超级管理员无限制
			$this->_allowed_routers[$user_id][] = $router;
			return true;
		}
		
		$actions = RoleService::service()->getActions($user_id);
		if(in_array($router, $actions)){
			//用户有此权限
			$this->_allowed_routers[$user_id][] = $router;
			return true;
		}
		
		$action = Actions::model()->fetchRow(array('router = ?'=>$router), 'is_public');
		//此路由并不在权限路由列表内，视为公共路由
		if(!$action || $action['is_public']){
			$this->_allowed_routers[$user_id][] = $router;
			return true;
		}
		
		$this->_denied_routers[$user_id][] = $router;
		return false;
	}
	
	/**
	 * 获取上一次登录信息（登录记录的倒数第二条）
	 * @param string $fields
	 * @param int $user_id 用户ID
	 * @return array|bool
	 */
	public function getLastLoginInfo($fields = '*', $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		
		return UserLogins::model()->fetchRow(array(
			'user_id = ?'=>$user_id,
		), $fields, 'id DESC', 1);
	}
	
	/**
	 * 判断指定用户是否是管理员
	 * @param int $user_id
	 * @return bool
	 */
	public function isAdmin($user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		
		if($user_id){
			$user = Users::model()->find($user_id, 'admin');
			return !empty($user['admin']);
		}else{
			//未登录，返回false
			return false;
		}
	}
}