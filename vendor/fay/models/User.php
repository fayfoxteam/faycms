<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Users;
use fay\models\tables\Props;
use fay\helpers\FieldHelper;
use fay\models\user\Profile;
use fay\models\user\Role;
use fay\models\tables\Roles;
use fay\models\tables\Actions;
use fay\models\tables\UserLogins;

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
	 * 以用户为单位，缓存经检查允许的路由
	 */
	private $_allowed_routers = array();
	
	/**
	 * 以用户为单位，缓存经检查不允许的路由
	 */
	private $_denied_routers = array();
	
	/**
	 * @return User
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
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
			$fields['user'] = Users::model()->getFields(array('password', 'salt'));
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
			if(in_array('*', $fields['props'])){
				$props = null;
			}else{
				$props = Prop::model()->mgetByAlias($fields['props'], Props::TYPE_ROLE);
			}
			$return['props'] = $this->getPropertySet($id, $props);
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
			$fields['user'] = Users::model()->getFields(array('password', 'salt'));
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
				if(in_array('*', $fields['props'])){
					$props = null;
				}else{
					$props = Prop::model()->mgetByAlias($fields['props'], Props::TYPE_ROLE);
				}
				$user['props'] = $this->getPropertySet($u['id'], $props);
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
	 * 根据路由做权限检查
	 * 从数据库中获取role.id和actions信息
	 * @param string $router 路由
	 * @param int $user_id 用户ID，若为空，则默认为当前登录用户
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
		
		$roles = Role::model()->getIds($user_id, true);
		if(in_array(Roles::ITEM_SUPER_ADMIN, $roles)){
			//超级管理员无限制
			$this->_allowed_routers[$user_id][] = $router;
			return true;
		}
		
		$actions = Role::model()->getActions($user_id, true);
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
	 * @param int $user_id 用户ID
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
	 */
	public function isAdmin($user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		
		if($user_id){
			$user = $this->get($user_id, 'user.admin');
			return !!$user['user']['admin'];
		}else{
			//未登录，返回false
			return false;
		}
	}
}