<?php
namespace fay\models\post;

use fay\core\Model;
use fay\core\Exception;
use fay\models\Post;
use fay\models\tables\PostFavorites;
use fay\helpers\ArrayHelper;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\Posts;

class Favorite extends Model{
	/**
	 * @param string $class_name
	 * @return Favorite
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 判断是否收藏过
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @return bool
	 * @throws Exception
	 */
	public static function isFavorited($post_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(PostFavorites::model()->find(array($user_id, $post_id), 'create_time')){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 批量判断是否收藏过
	 * @param array $post_ids 由文章ID组成的一维数组
	 * @param int|null $user_id 用户ID，默认为当前登录用户
	 * @return array
	 * @throws Exception
	 */
	public static function mIsFavorited($post_ids, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(!is_array($post_ids)){
			$post_ids = explode(',', str_replace(' ', '', $post_ids));
		}
		
		$favorites = PostFavorites::model()->fetchAll(array(
			'user_id = ?'=>$user_id,
			'post_id IN (?)'=>$post_ids,
		), 'post_id');
		
		$favorite_map = ArrayHelper::column($favorites, 'post_id');
		
		$return = array();
		foreach($post_ids as $p){
			$return[$p] = in_array($p, $favorite_map);
		}
		return $return;
	}
	
	/**
	 * 获取收藏列表
	 * @param string $fields 文章字段
	 * @param int $page
	 * @param int $page_size
	 * @param int|null $user_id 用户ID，默认为当前登录用户
	 * @return array
	 * @throws Exception
	 */
	public function getList($fields, $page = 1, $page_size = 20, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		$sql = new Sql();
		$sql->from(array('pf'=>'post_favorites'), 'post_id')
			->joinLeft(array('p'=>'posts'), 'pf.post_id = p.id')
			->where('pf.user_id = ?', $user_id)
			->where(array(
				'deleted = 0',
				'publish_time < '.\F::app()->current_time,
				'status = '.Posts::STATUS_PUBLISHED,
			))
			->order('pf.create_time DESC')
		;
		
		$listview = new ListView($sql, array(
			'page_size'=>$page_size,
			'current_page'=>$page,
		));
		
		$favorites = $listview->getData();
		
		if(!$favorites){
			return array();
		}
		
		return array(
			'favorites'=>Post::model()->mget(ArrayHelper::column($favorites, 'post_id'), $fields),
			'pager'=>$listview->getPager(),
		);
	}
}