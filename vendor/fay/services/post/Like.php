<?php
namespace fay\services\post;

use fay\core\Model;
use fay\core\Hook;
use fay\core\Exception;
use fay\models\tables\PostLikes;
use fay\models\User;
use fay\models\Post;
use fay\helpers\ArrayHelper;

class Like extends Model{
	/**
	 * @return Like
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 给文章点赞
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @param bool $sockpuppet 马甲信息
	 */
	public static function like($post_id, $user_id = null, $sockpuppet = 0){
		if($user_id === null){
			$user_id = \F::app()->current_user;
		}else if(!User::isUserIdExist($user_id)){
			throw new Exception('指定用户ID不存在', 'the-given-user-id-is-not-exist');
		}
		
		if(!Post::isPostIdExist($post_id)){
			throw new Exception('指定的文章ID不存在', 'the-given-post-id-is-not-exist');
		}
		
		if(self::isLiked($post_id, $user_id)){
			throw new Exception('已赞过，不能重复点赞', 'already-liked');
		}
		
		PostLikes::model()->insert(array(
			'post_id'=>$post_id,
			'user_id'=>$user_id,
			'create_time'=>\F::app()->current_time,
			'sockpuppet'=>$sockpuppet,
		));
		
		Hook::getInstance()->call('after_post_like');
	}
	
	/**
	 * 取消点赞
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function unlike($post_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		//删除点赞关系
		PostLikes::model()->delete(array(
			'user_id = ?'=>$user_id,
			'post_id = ?'=>$post_id,
		));
		
		Hook::getInstance()->call('after_post_unlike');
	}
	
	/**
	 * 判断是否赞过
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function isLiked($post_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(PostLikes::model()->find(array($post_id, $user_id))){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 批量判断是否赞过
	 * @param array $post_ids 由文章ID组成的一维数组
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function mIsLiked($post_ids, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(!is_array($post_ids)){
			$post_ids = explode(',', str_replace(' ', '', $post_ids));
		}
		
		$likes = PostLikes::model()->fetchAll(array(
			'user_id = ?'=>$user_id,
			'post_id IN (?)'=>$post_ids,
		), 'post_id');
		
		$like_map = ArrayHelper::column($likes, 'post_id');
		
		$return = array();
		foreach($post_ids as $p){
			$return[$p] = in_array($p, $like_map);
		}
		
		return $return;
	}
}