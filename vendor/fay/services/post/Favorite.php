<?php
namespace fay\services\post;

use fay\core\Model;
use fay\core\Hook;
use fay\core\Exception;
use fay\models\User;
use fay\models\Post;
use fay\models\tables\PostFavorites;
use fay\helpers\Request;
use fay\models\post\Favorite as FavoriteModel;
use fay\models\tables\PostMeta;

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
		
		if(FavoriteModel::isFavorited($post_id, $user_id)){
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
		
		//文章收藏数+1
		if($sockpuppet){
			//非真实用户行为
			PostMeta::model()->incr($post_id, array('favorites'), 1);
		}else{
			//真实用户行为
			PostMeta::model()->incr($post_id, array('favorites', 'real_favorites'), 1);
		}
		
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
		
		$favorite = PostFavorites::model()->find(array($user_id, $post_id), 'sockpuppet');
		if($favorite){
			//删除收藏关系
			PostFavorites::model()->delete(array(
				'user_id = ?'=>$user_id,
				'post_id = ?'=>$post_id,
			));
			
			//文章收藏数-1
			if($favorite['sockpuppet']){
				//非真实用户行为
				PostMeta::model()->incr($post_id, array('favorites'), -1);
			}else{
				//真实用户行为
				PostMeta::model()->incr($post_id, array('favorites', 'favorites'), -1);
			}
				
			//执行钩子
			Hook::getInstance()->call('after_post_unfavorite');
				
			return true;
		}else{
			//未点赞
			return false;
		}
		
		Hook::getInstance()->call('after_post_unfavorite');
	}
}