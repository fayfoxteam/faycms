<?php
namespace fay\services;

use fay\core\Model;
use fay\helpers\ArrayHelper;
use fay\core\Hook;
use fay\helpers\Request;
use fay\core\db\Expr;
use fay\models\tables\UserProfile;
use fay\models\Option;
use fay\models\Category;
use fay\models\tables\RolesCats;
use fay\models\tables\Roles;
use fay\core\Sql;
use fay\models\tables\Users;
use fay\models\User as UserModel;
use fay\models\tables\UsersRoles;
use fay\models\user\Password;
use fay\models\tables\UserCounter;
use fay\models\Prop;
use fay\core\Exception;
use fay\models\tables\UserLogins;

/**
 * 用户服务
 */
class User extends Model{
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
		$user = UserModel::model()->get($user['id'], array(
			'user'=>array('id', 'username', 'nickname', 'avatar', 'status', 'admin'),
			'profile'=>array('last_login_time', 'last_login_ip'),
			'roles'=>'id',
		));
		$this->setSessionInfo($user);
		
		$role_ids = ArrayHelper::column($user['roles'], 'id');
		//设置权限，超级管理员无需设置
		if(!in_array(Roles::ITEM_SUPER_ADMIN, $role_ids)){
			if($role_ids){
				//分类权限
				if(Option::get('system:post_role_cats')){
					//未分类文章任何人都有权限编辑
					$post_root = Category::model()->get('_system_post', 'id');
					\F::session()->set('role_cats', array_merge(array(0, $post_root['id']), RolesCats::model()->fetchCol('cat_id', 'role_id IN ('.implode(',', $role_ids).')')));
				}
			}
		}
		
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
			'mac'=>'',//@todo 通过fmac获取对应的id
		));
		
		Hook::getInstance()->call('after_login', array(
			'user'=>$user,
		));
		
		return array(
			'status'=>1,
			'user'=>$user,
		);
	}
	
	/**
	 * 设置登录session
	 */
	private function setSessionInfo($user){
		\F::session()->set('user', array(
			'id'=>$user['user']['id'],
			'username'=>$user['user']['username'],
			'nickname'=>$user['user']['nickname'],
			'avatar'=>$user['user']['avatar'],
			'roles'=>ArrayHelper::column($user['roles'], 'id'),
			'status'=>$user['user']['status'],
			'admin'=>$user['user']['admin'],
		));
	}
	
	/**
	 * 退出登录，销毁session
	 */
	public function logout(){
		\F::session()->remove();
	}
	
	/**
	 * 新增一个用户属性集
	 * @param int $user_id 用户ID
	 * @param array $data 以属性ID为键的属性键值数组
	 * @param null|array $props 属性。若为null，则根据用户ID获取属性
	 */
	public function createPropertySet($user_id, $data, $props = null){
		if($props === null){
			$props = UserModel::model()->getProps($user_id);
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
			$props = UserModel::model()->getProps($user_id);
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
		$user = Users::model()->fillData($user, false, 'insert');
		$user['admin'] = $is_admin ? 1 : 0;
		
		//信息验证（用户信息很重要，在入库前必须再做一次验证）
		$config = Option::mget(array(
			'system:user_nickname_required',
			'system:user_nickname_unique'
		));
		if(!isset($user['username']) || $user['username'] == ''){
			throw new Exception('用户名不能为空', 'missing-parameter:username');
		}
		if($config['system:user_nickname_required'] && !isset($user['nickname']) || $user['nickname'] == ''){
			throw new Exception('用户昵称不能为空', 'missing-parameter:nickname');
		}
		
		if(Users::model()->fetchRow(array(
			'username = ?'=>$user['username'],
		))){
			throw new Exception('用户名已存在', 'invalid-parameter:username-is-exist');
		}
		
		if($config['system:user_nickname_unique'] && Users::model()->fetchRow(array(
			'nickname = ?'=>$user['nickname'],
		))){
			throw new Exception('用户昵称已存在', 'invalid-parameter:username-is-exist');
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
		}
		
		if(isset($extra['profile'])){
			UserProfile::model()->update($extra['profile'], $user_id, true);
		}
		
		//附加属性
		if(isset($extra['props'])){
			$this->updatePropertySet($user_id, $extra['props']);
		}
	}
}