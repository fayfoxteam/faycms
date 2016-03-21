<?php
namespace fay\models\feed;

use fay\core\Model;
use fay\core\Exception;
use fay\models\tables\FeedLikes;
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
	 * @param int $feed_id 动态ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function isLiked($feed_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(FeedLikes::model()->find(array($feed_id, $user_id))){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 批量判断是否赞过
	 * @param array $feed_ids 由动态ID组成的一维数组
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function mIsLiked($feed_ids, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(!is_array($feed_ids)){
			$feed_ids = explode(',', str_replace(' ', '', $feed_ids));
		}
		
		$likes = FeedLikes::model()->fetchAll(array(
			'user_id = ?'=>$user_id,
			'feed_id IN (?)'=>$feed_ids,
		), 'feed_id');
		
		$like_map = ArrayHelper::column($likes, 'feed_id');
		
		$return = array();
		foreach($feed_ids as $p){
			$return[$p] = in_array($p, $like_map);
		}
		
		return $return;
	}
}