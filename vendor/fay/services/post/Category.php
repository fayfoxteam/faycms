<?php
namespace fay\services\post;

use fay\core\ErrorException;
use fay\core\Service;
use fay\core\Sql;
use fay\models\tables\Categories;
use fay\models\tables\Posts;
use fay\models\tables\PostsCategories;
use fay\services\Option;
use fay\services\user\Role;
use fay\models\tables\Roles;
use fay\models\tables\RolesCats;
use fay\services\Category as CategoryService;

class Category extends Service{
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
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
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
	 * 获取文章附加分类ID
	 * @param int $post_id 文章ID
	 * @return array 由分类ID构成的一维数组
	 */
	public function getSecondaryCatIds($post_id){
		return PostsCategories::model()->fetchCol('cat_id', array(
			'post_id = ?'=>$post_id
		));
	}
	
	/**
	 * 获取文章主分类ID
	 * @param int $post_id 文章ID
	 * @return int 分类ID
	 */
	public function getPrimaryCatId($post_id){
		$post = Posts::model()->find($post_id, 'cat_id');
		return $post['cat_id'];
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
		
		if(Role::service()->is(Roles::ITEM_SUPER_ADMIN, $user_id)){
			//如果是超级管理员，设置一个*
			return $this->_user_allowed_cats[$user_id] = array('*');
		}
		
		//获取文章根分类（未分类），任何人都可以编辑未分类文章
		$post_root = CategoryService::service()->get('_system_post', 'id');
		
		//获取用户角色ID
		$role_ids = Role::service()->getIds($user_id);
		
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
			if(Role::service()->is(Roles::ITEM_SUPER_ADMIN, $user_id)){
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
	
	/**
	 * 文章相关分类文章数加一（包含主分类和附加分类）
	 * @param int $post_id 文章ID
	 * @return int 受影响的分类数
	 */
	public function incr($post_id){
		//主分类
		$primary_cat_id = $this->getPrimaryCatId($post_id);
		//附加分类
		$secondary_cat_id = $this->getSecondaryCatIds($post_id);
		
		if($primary_cat_id){
			//若主分类非0，附加到附加分类里，等会儿一起更新
			$secondary_cat_id[] = $primary_cat_id;
		}
		
		if($secondary_cat_id){
			return Categories::model()->incr(array(
				'id IN (?)'=>$secondary_cat_id
			), 'count', 1);
		}else{
			return 0;
		}
	}
	
	/**
	 * 文章相关分类文章数减一（包含主分类和附加分类）
	 * @param int $post_id 文章ID
	 * @return int 受影响的分类数
	 */
	public function decr($post_id){
		//主分类
		$primary_cat_id = $this->getPrimaryCatId($post_id);
		//附加分类
		$secondary_cat_id = $this->getSecondaryCatIds($post_id);
		
		if($primary_cat_id){
			//若主分类非0，附加到附加分类里，等会儿一起更新
			$secondary_cat_id[] = $primary_cat_id;
		}
		
		if($secondary_cat_id){
			return Categories::model()->incr(array(
				'id IN (?)'=>$secondary_cat_id
			), 'count', -1);
		}else{
			return 0;
		}
	}
	
	/**
	 * 设置附加分类（创建或编辑文章时调用）
	 * @param int $primary_cat_id 主分类ID
	 * @param string|array $secondary_cat_ids 逗号分割的分类ID，或由分类ID构成的一维数组。若为空，则删除指定文章的所有附加分类
	 * @param int $post_id 文章ID
	 * @param int|null $old_status 文章原状态
	 * @param int|null $new_status 文章新状态
	 * @throws ErrorException
	 */
	public function setSecondaryCats($primary_cat_id, $secondary_cat_ids, $post_id, $old_status, $new_status){
		if($secondary_cat_ids){
			if(!is_array($secondary_cat_ids)){
				$secondary_cat_ids = explode(',', $secondary_cat_ids);
			}
		}else{
			$secondary_cat_ids = array();
		}
		
		//若主分类在附加分类中，则将其从附加分类中移除
		$key = array_search($primary_cat_id, $secondary_cat_ids);
		if($key !== false){
			unset($secondary_cat_ids[$key]);
		}
		
		//验证分类ID是否存在
		if($secondary_cat_ids && count(Categories::model()->fetchAll(array(
			'id IN (?)'=>$secondary_cat_ids,
		), 'id')) != count($secondary_cat_ids)){
			//实际存在的分类记录数与输入记录数不相等，意味着有指定分类ID不存在
			throw new ErrorException('指定附加分类不存在');
		}
		
		$old_cat_ids = array();
		$deleted_cat_ids = array();
		if($old_status !== null){
			//原状态非null，说明是编辑文章，需要获取文章原标签，删掉已经被删掉的标签
			$old_cat_ids = PostsCategories::model()->fetchCol('cat_id', array(
				'post_id = ?'=>$post_id,
			));
			
			//删除已被删除的标签
			$deleted_cat_ids = array_diff($old_cat_ids, $secondary_cat_ids);
			//若主分类本来在附加分类中，则将其从附加分类中删除
			if(in_array($primary_cat_id, $old_cat_ids)){
				$deleted_cat_ids[] = $primary_cat_id;
			}
			if($deleted_cat_ids){
				PostsCategories::model()->delete(array(
					'post_id = ?'=>$post_id,
					'cat_id IN (?)'=>$deleted_cat_ids
				));
			}
		}
		
		//插入新的标签
		if($old_cat_ids){
			$new_cat_ids = array_diff($secondary_cat_ids, $old_cat_ids);
		}else{
			$new_cat_ids = $secondary_cat_ids;
		}
		if($new_cat_ids){
			foreach($new_cat_ids as $v){
				PostsCategories::model()->insert(array(
					'post_id'=>$post_id,
					'cat_id'=>$v,
				));
			}
		}
		
		if($old_status === null && $new_status == Posts::STATUS_PUBLISHED){
			//没有原状态，说明是新增文章，且文章状态为已发布：所有输入分类文章数加一
			CategoryService::service()->incr($secondary_cat_ids);
		}else if($old_status == Posts::STATUS_PUBLISHED && $new_status != Posts::STATUS_PUBLISHED){
			//本来处于已发布状态，编辑后变成未发布：文章原分类文章数减一
			CategoryService::service()->incr($old_cat_ids);
		}else if($old_status != Posts::STATUS_PUBLISHED && $new_status == Posts::STATUS_PUBLISHED){
			//本来是未发布状态，编辑后变成已发布：所有输入分类文章数加一
			CategoryService::service()->incr($secondary_cat_ids);
		}else if($old_status == Posts::STATUS_PUBLISHED && $new_status == Posts::STATUS_PUBLISHED){
			//本来是已发布状态，编辑后还是已发布状态：新增分类文章数加一，被删除分类文章数减一
			if($new_cat_ids){
				CategoryService::service()->incr($new_cat_ids);
			}
			if($deleted_cat_ids){
				CategoryService::service()->decr($deleted_cat_ids);
			}
		}else if($old_status == Posts::STATUS_PUBLISHED && $new_status === null){
			//本来是已发布状态，编辑时并未编辑状态：新增分类文章数加一，被删除分类文章数减一
			if($new_cat_ids){
				CategoryService::service()->incr($new_cat_ids);
			}
			if($deleted_cat_ids){
				CategoryService::service()->decr($deleted_cat_ids);
			}
		}
	}
	
	/**
	 * 更新文章主分类文章数（创建或编辑文章时调用）
	 * @param int|null $old_cat_id 文章原分类
	 * @param int|null $new_cat_id 文章新分类
	 * @param int|null $old_status 文章原状态
	 * @param int|null $new_status 文章新状态
	 */
	public function updatePrimaryCatCount($old_cat_id, $new_cat_id, $old_status, $new_status){
		if($old_cat_id === null){
			if($new_cat_id && $new_status == Posts::STATUS_PUBLISHED){
				//$old_cat_id为null，说明是新增文章，若主分类非0，且文章状态为已发布，主分类文章数加一
				CategoryService::service()->incr($new_cat_id);
			}
		//从这里开始，以下都是编辑文章的情况
		}else if($old_status == Posts::STATUS_PUBLISHED && $new_status != Posts::STATUS_PUBLISHED){
			//本来处于已发布状态，编辑后变成未发布：原主分类文章数减一
			CategoryService::service()->decr($old_cat_id);
		}else if($old_status != Posts::STATUS_PUBLISHED && $new_status == Posts::STATUS_PUBLISHED){
			//本来处于未发布状态，编辑后变成已发布：新主分类文章数加一
			CategoryService::service()->incr($new_cat_id);
		}else if($old_status == Posts::STATUS_PUBLISHED &&
			($new_status == Posts::STATUS_PUBLISHED || $new_status === null) &&
			$old_cat_id != $new_cat_id
		){
			//本来处于已发布状态，且编辑后还是已发布或未编辑状态，且编辑了主分类：原主分类文章数减一，新主分类文章数加一
			CategoryService::service()->decr($old_cat_id);
			CategoryService::service()->incr($new_cat_id);
		}
	}
}