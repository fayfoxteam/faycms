<?php
namespace fay\models\post;

use fay\core\Model;
use fay\core\Hook;
use fay\core\Exception;
use fay\models\tables\PostLikes;
use fay\models\User;
use fay\models\Post;

class Like extends Model{
	/**
	 * @return Like
	 */
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 给文章点赞
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @param bool $is_real 是否真实用户（社交网站总是免不了要做个假）
	 */
	public static function like($post_id, $user_id = null, $is_real = true){
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
			'is_real'=>$is_real ? 1 : 0,
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
		
	}
	
	/**
	 * 批量判断是否赞过
	 * @param array $post_ids 由文章ID组成的一维数组
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function mIsLiked($post_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
	}
}