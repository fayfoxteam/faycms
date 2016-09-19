<?php
namespace fay\services\user;

use fay\core\Service;
use fay\core\Sql;
use fay\helpers\FieldHelper;
use fay\models\tables\Roles;
use fay\models\tables\UsersRoles;
use fay\helpers\ArrayHelper;

class Role extends Service{
	/**
	 * 默认返回字段
	 */
	public static $public_fields = array('id', 'title', 'description');
	
	/**
	 * @param string $class_name
	 * @return Role
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 获取角色信息
	 * @param int $user_id 用户ID
	 * @param string $fields 角色字段（roles表字段）
	 * @return array 返回包含用户角色信息的二维数组
	 */
	public function get($user_id, $fields = null){
		//若传入$fields为空，则返回默认字段
		$fields || $fields = self::$public_fields;
		
		//格式化fields
		$fields = FieldHelper::parse($fields, null, self::$public_fields);
		
		$sql = new Sql();
		return $sql->from(array('ur'=>'users_roles'), '')
			->joinLeft(array('r'=>'roles'), 'ur.role_id = r.id', Roles::model()->formatFields($fields['fields']))
			->where('ur.user_id = ?', $user_id)
			->where('r.deleted = 0')
			->fetchAll();
	}
	
	/**
	 * 批量获取角色信息
	 * @param array $user_ids 用户ID一维数组
	 * @param string $fields 角色字段（roles表字段）
	 * @return array 返回以用户ID为key的三维数组
	 */
	public function mget($user_ids, $fields = null){
		//若传入$fields为空，则返回默认字段
		$fields || $fields = self::$public_fields;
		
		//格式化fields
		$fields = FieldHelper::parse($fields, null, self::$public_fields);
		
		$sql = new Sql();
		$roles = $sql->from(array('ur'=>'users_roles'), 'user_id')
			->joinLeft(array('r'=>'roles'), 'ur.role_id = r.id', Roles::model()->formatFields($fields['fields']))
			->where('ur.user_id IN (?)', $user_ids)
			->where('r.deleted = 0')
			->fetchAll();
		$return = array_fill_keys($user_ids, array());
		foreach($roles as $r){
			$u = $r['user_id'];
			unset($r['user_id']);
			$return[$u][] = $r;
		}
		return $return;
	}
	
	/**
	 * 返回指定用户的角色ID
	 * @param int $user_id 用户ID
	 * @return array 角色ID构成的一维数组
	 */
	public function getIds($user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		
		if(!$user_id){
			return array();
		}
		
		//取缓存
		$role_ids = \F::cache()->get("user.role_ids.{$user_id}");
		if($role_ids){
			return $role_ids;
		}
		
		$user_roles = UsersRoles::model()->fetchAll(array('user_id = ?'=>$user_id), 'role_id');
		$role_ids = ArrayHelper::column($user_roles, 'role_id');
		
		//设置缓存1小时
		\F::cache()->set("user.role_ids.{$user_id}", $role_ids, 3600);
		
		return $role_ids;
	}
	
	/**
	 * 返回指定用户的权限路由（一维数组）
	 * @param int $user_id 用户ID
	 * @return array 角色ID构成的一维数组
	 */
	public function getActions($user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		
		if(!$user_id){
			return array();
		}
		
		//取缓存
		$actions = \F::cache()->get("user.actions.{$user_id}");
		if($actions){
			return $actions;
		}
		
		//如果走缓存，则获取角色ID的时候也走缓存
		$role_ids = $this->getIds($user_id);
		if($role_ids){
			$sql = new Sql();
			$actions = $sql->from(array('ra'=>'roles_actions'), '')
				->joinLeft(array('a'=>'actions'), 'ra.action_id = a.id', 'router')
				->where('ra.role_id IN ('.implode(',', $role_ids).')')
				->group('a.router')
				->fetchAll();
			
			$actions = ArrayHelper::column($actions, 'router');
		}else{
			$actions = array();
		}
		
		//设置缓存1小时
		\F::cache()->set("user.actions.{$user_id}", $actions, 3600);
		
		return $actions;
	}
	
	/**
	 * 判断一个用户是否属于指定角色
	 * @param int $role_id 角色ID
	 * @param int $user_id 用户ID
	 * @return bool
	 */
	public function is($role_id, $user_id = null){
		if($user_id === null){
			$user_id = \F::app()->current_user;
		}
		
		$user_roles = $this->getIds($user_id);
		return in_array($role_id, $user_roles);
	}
}