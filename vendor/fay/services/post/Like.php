<?php
namespace fay\services\post;

use fay\core\Service;
use fay\core\Hook;
use fay\core\Exception;
use fay\models\tables\PostLikes;
use fay\models\User;
use fay\models\Post;
use fay\models\tables\PostMeta;
use fay\models\post\Like as LikeModel;

class Like extends Service{
	/**
	 * @return Like
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 给文章点赞
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @param bool $sockpuppet 马甲信息
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
		
		if(LikeModel::isLiked($post_id, $user_id)){
			throw new Exception('已赞过，不能重复点赞', 'already-liked');
		}
		
		PostLikes::model()->insert(array(
			'post_id'=>$post_id,
			'user_id'=>$user_id,
			'create_time'=>\F::app()->current_time,
			'trackid'=>$trackid,
			'sockpuppet'=>$sockpuppet,
		));
		
		//文章点赞数+1
		if($sockpuppet){
			//非真实用户行为
			PostMeta::model()->incr($post_id, array('likes'), 1);
		}else{
			//真实用户行为
			PostMeta::model()->incr($post_id, array('likes', 'real_likes'), 1);
		}
		
		//执行钩子
		Hook::getInstance()->call('after_post_like');
	}
	
	/**
	 * 取消点赞
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function remove($post_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		$like = PostLikes::model()->find(array($post_id, $user_id), 'sockpuppet');
		if($like){
			//删除点赞关系
			PostLikes::model()->delete(array(
				'user_id = ?'=>$user_id,
				'post_id = ?'=>$post_id,
			));
				
			if($like['sockpuppet']){
				//非真实用户行为
				PostMeta::model()->incr($post_id, array('likes'), -1);
			}else{
				//真实用户行为
				PostMeta::model()->incr($post_id, array('likes', 'real_likes'), -1);
			}
				
			//执行钩子
			Hook::getInstance()->call('after_feed_unlike');
				
			return true;
		}else{
			//未点赞
			return false;
		}
	}
}