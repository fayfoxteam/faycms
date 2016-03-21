<?php
namespace fay\models\post;

use fay\core\Model;
use fay\core\Exception;
use fay\models\tables\PostLikes;
use fay\helpers\ArrayHelper;

class Like extends Model{
	/**
	 * @return Like
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
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