<?php
namespace fay\models\feed;

use fay\core\Model;
use fay\core\Exception;
use fay\models\tables\FeedFavorites;
use fay\helpers\ArrayHelper;

class Favorite extends Model{
	/**
	 * @return Favorite
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 判断是否收藏过
	 * @param int $feed_id 动态ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function isFavorited($feed_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(FeedFavorites::model()->find(array($user_id, $feed_id), 'create_time')){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 批量判断是否收藏过
	 * @param array $feed_ids 由动态ID组成的一维数组
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function mIsFavorited($feed_ids, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(!is_array($feed_ids)){
			$feed_ids = explode(',', str_replace(' ', '', $feed_ids));
		}
		
		$favorites = FeedFavorites::model()->fetchAll(array(
			'user_id = ?'=>$user_id,
			'feed_id IN (?)'=>$feed_ids,
		), 'feed_id');
		
		$favorite_map = ArrayHelper::column($favorites, 'feed_id');
		
		$return = array();
		foreach($feed_ids as $p){
			$return[$p] = in_array($p, $favorite_map);
		}
		return $return;
	}
}