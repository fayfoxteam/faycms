<?php
namespace fay\models\post;

use fay\core\Model;
use fay\core\Sql;
use fay\models\tables\Categories;
use fay\models\Option;
use fay\models\user\Role;
use fay\models\tables\Roles;
use fay\models\tables\RolesCats;
use fay\models\Category as CategoryModel;

class Category extends Model{
	/**
	 * 默认返回字段
	 */
	private $default_fields = array('id', 'title');
	
	/**
	 * 以用户为单位，缓存用户所具备的分类权限
	 */
	private $_user_allowed_cats = array();
	
	/**
	 * @param string $class_name
	 * @return Category
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 获取文章附加分类
	 * @param int $post_id 文章ID
	 * @param string $fields 分类字段（categories表字段）
	 * @return array 返回包含分类信息的二维数组
	 */
	public function get($post_id, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = $this->default_fields;
		}
		
		$sql = new Sql();
		return $sql->from(array('pc'=>'posts_categories'), '')
			->joinLeft(array('c'=>'categories'), 'pc.cat_id = c.id', Categories::model()->formatFields($fields))
			->where(array('pc.post_id = ?'=>$post_id))
			->fetchAll();
	}
	
	/**
	 * 批量获取文章附加分类
	 * @param array $post_ids 文章ID构成的二维数组
	 * @param string $fields 分类字段（categories表字段）
	 * @return array 返回以文章ID为key的三维数组
	 */
	public function mget($post_ids, $fields = null){
		if(empty($fields) || empty($fields[0])){
			//若传入$fields为空，则返回默认字段
			$fields = $this->default_fields;
		}
		
		$sql = new Sql();
		$cats = $sql->from(array('pc'=>'posts_categories'), 'post_id')
			->joinLeft(array('c'=>'categories'), 'pc.cat_id = c.id', Categories::model()->formatFields($fields))
			->where(array('pc.post_id IN (?)'=>$post_ids))
			->fetchAll();
		$return = array_fill_keys($post_ids, array());
		foreach($cats as $c){
			$p = $c['post_id'];
			unset($c['post_id']);
			$return[$p][] = $c;
		}
		return $return;
	}
	
	/**
	 * 获取用户分类权限
	 * 此函数并不检查“文章分类权限控制”开关是否打开
	 * @param string $user_id 用户ID，默认为当前登录用户ID
	 * @param bool $cache 是否缓存
	 * @return array
	 */
	public function getAllowedCatIds($user_id = null, $cache = true){
		$user_id || $user_id = \F::app()->current_user;
		
		if($cache && isset($this->_user_allowed_cats[$user_id])){
			return $this->_user_allowed_cats[$user_id];
		}
		
		if(Role::model()->is(Roles::ITEM_SUPER_ADMIN, $user_id, true)){
			//如果是超级管理员，设置一个*
			return $this->_user_allowed_cats[$user_id] = array('*');
		}
		
		//获取文章根分类（未分类），任何人都可以编辑未分类文章
		$post_root = CategoryModel::model()->get('_system_post', 'id');
		
		//获取用户角色ID
		$role_ids = Role::model()->getIds($user_id);
		
		//获取角色属性和0和
		$allowed_cats = array_merge(
			array('0', $post_root['id']),
			RolesCats::model()->fetchCol('cat_id', 'role_id IN ('.implode(',', $role_ids).')')
		);
		
		return $this->_user_allowed_cats[$user_id] = $allowed_cats;
	}
	
	/**
	 * 判断用户是否具备编辑指定分类的权限
	 * @param int $cat_id 分类ID
	 * @param int $user_id 用户ID，默认为当前登录用户ID
	 * @return bool
	 */
	public function isAllowedCat($cat_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		
		if(Option::get('system:post_role_cats')){
			//开启了文章分类权限控制，进行验证
			if(Role::model()->is(Roles::ITEM_SUPER_ADMIN, $user_id, true)){
				//如果是超级管理员，返回true
				return true;
			}
			
			$allowed_cats = $this->getAllowedCatIds($user_id, true);
			
			return in_array('*', $allowed_cats) || in_array($cat_id, $allowed_cats);
		}else{
			//未开启文章分类权限控制，直接返回true
			return true;
		}
	}
}