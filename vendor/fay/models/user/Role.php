<?php
namespace fay\models\user;

use fay\core\Model;
use fay\core\Sql;
use fay\models\tables\Roles;

class Role extends Model{
	/**
	 * @return Role
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取角色信息
	 * @param int $user_id 用户ID
	 * @param string $fields 角色字段（roles表字段）
	 * @return array 返回包含用户角色信息的二维数组
	 */
	public function get($user_id, $fields = 'id,title'){
		$sql = new Sql();
		return $sql->from('users_roles', 'ur', '')
			->joinLeft('roles', 'r', 'ur.role_id = r.id', Roles::model()->formatFields($fields))
			->where('ur.user_id = ?', $user_id)
			->fetchAll();
	}
	
	/**
	 * 批量获取角色信息
	 * @param array $user_ids 用户ID一维数组
	 * @param string $fields 角色字段（roles表字段）
	 * @return array 返回以用户ID为key的三维数组
	 */
	public function mget($user_ids, $fields = 'id,title'){
		$sql = new Sql();
		$roles = $sql->from('users_roles', 'ur', 'user_id')
			->joinLeft('roles', 'r', 'ur.role_id = r.id', Roles::model()->formatFields($fields))
			->where('ur.user_id IN (?)', $user_ids)
			->fetchAll();
		$return = array_fill_keys($user_ids, array());
		foreach($roles as $r){
			$u = $r['user_id'];
			unset($r['user_id']);
			$return[$u][] = $r;
		}
		return $return;
	}
}