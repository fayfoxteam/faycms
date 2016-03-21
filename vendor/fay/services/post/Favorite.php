<?php
namespace fay\services\post;

use fay\core\Model;
use fay\core\Hook;
use fay\core\Exception;
use fay\models\User;
use fay\models\Post;
use fay\models\tables\PostFavorites;
use fay\helpers\ArrayHelper;
use fay\helpers\Request;
use fay\core\Sql;
use fay\common\ListView;

class Favorite extends Model{
	/**
	 * @return Favorite
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 收藏文章
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function add($post_id, $trackid = '', $user_id = null, $sockpuppet = 0){
		if($user_id === null){
			$user_id = \F::app()->current_user;
		}else if(!User::isUserIdExist($user_id)){
			throw new Exception('指定用户ID不存在', 'the-given-user-id-is-not-exist');
		}
		
		if(!Post::isPostIdExist($post_id)){
			throw new Exception('指定的文章ID不存在', 'the-given-post-id-is-not-exist');
		}
		
		if(self::isFavorited($post_id, $user_id)){
			throw new Exception('已收藏，不能重复收藏', 'already-favorited');
		}
		
		PostFavorites::model()->insert(array(
			'user_id'=>$user_id,
			'post_id'=>$post_id,
			'trackid'=>$trackid,
			'sockpuppet'=>$sockpuppet,
			'create_time'=>\F::app()->current_time,
			'ip_int'=>Request::ip2int(\F::app()->ip),
		));
		
		Hook::getInstance()->call('after_post_favorite');
	}
	
	/**
	 * 取消收藏
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function remove($post_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		//删除收藏关系
		PostFavorites::model()->delete(array(
			'user_id = ?'=>$user_id,
			'post_id = ?'=>$post_id,
		));
		
		Hook::getInstance()->call('after_post_unfavorite');
	}
	
	/**
	 * 判断是否收藏过
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function isFavorited($post_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(PostFavorites::model()->find(array($post_id, $user_id))){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 批量判断是否收藏过
	 * @param array $post_ids 由文章ID组成的一维数组
	 * @param int $user_id 用户ID，默认为当前登录用户
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
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public function getList($fields, $page = 1, $page_size = 20, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		$sql = new Sql();
		$sql->from(array('pf'=>'post_favorites'), 'post_id')
			->where('pf.user_id = ?', $user_id)
			->order('pf.create_time DESC')
		;
		
		$listview = new ListView($sql, array(
			'page_size'=>$page_size,
		));
		
		$favorites = $listview->getData();
		$posts = Post::model();
	}
}