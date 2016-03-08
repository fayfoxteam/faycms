<?php
namespace fay\models\user;

use fay\core\Model;
use fay\core\Sql;
use fay\models\tables\Roles;
use fay\models\tables\UsersRoles;
use fay\helpers\ArrayHelper;

class Role extends Model{
	/**
	 * 默认返回字段
	 */
	private $public_fields = array('id', 'title', 'description');
	
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
	public function get($user_id, $fields = null){
		if(empty($fields)){
			//若传入$fields为空，则返回默认字段
			$fields = $this->public_fields;
		}else{
			if(!is_array($fields)){
				$fields = explode(',', $fields);
			}
			if(in_array('*', $fields)){
				$fields = $this->public_fields;
			}else{
				$fields = array_intersect($this->public_fields, $fields);
			}
		}
		$sql = new Sql();
		return $sql->from(array('ur'=>'users_roles'), '')
			->joinLeft(array('r'=>'roles'), 'ur.role_id = r.id', Roles::model()->formatFields($fields))
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
		if(empty($fields)){
			//若传入$fields为空，则返回默认字段
			$fields = $this->public_fields;
		}else{
			if(!is_array($fields)){
				$fields = explode(',', $fields);
			}
			if(in_array('*', $fields)){
				$fields = $this->public_fields;
			}else{
				$fields = array_intersect($this->public_fields, $fields);
			}
		}
		$sql = new Sql();
		$roles = $sql->from(array('ur'=>'users_roles'), 'user_id')
			->joinLeft(array('r'=>'roles'), 'ur.role_id = r.id', Roles::model()->formatFields($fields))
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
	public function getIds($user_id){
		$user_roles = UsersRoles::model()->fetchAll(array('user_id = ?'=>$user_id), 'role_id');
		return ArrayHelper::column($user_roles, 'role_id');
	}
}