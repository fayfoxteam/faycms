<?php
namespace fay\services\feed;

use fay\core\Model;
use fay\core\Hook;
use fay\core\Exception;
use fay\models\User;
use fay\models\Post;
use fay\models\tables\FeedFavorites;
use fay\helpers\ArrayHelper;
use fay\models\tables\FeedMeta;

class Favorite extends Model{
	/**
	 * @return Favorite
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 收藏动态
	 * @param int $feed_id 动态ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function favorite($feed_id, $trackid = '', $user_id = null, $sockpuppet = 0){
		if($user_id === null){
			$user_id = \F::app()->current_user;
		}else if(!User::isUserIdExist($user_id)){
			throw new Exception('指定用户ID不存在', 'the-given-user-id-is-not-exist');
		}
		
		if(!Post::isPostIdExist($feed_id)){
			throw new Exception('指定的动态ID不存在', 'the-given-feed-id-is-not-exist');
		}
		
		if(self::isFavorited($feed_id, $user_id)){
			throw new Exception('已收藏，不能重复收藏', 'already-favorited');
		}
		
		FeedFavorites::model()->insert(array(
			'user_id'=>$user_id,
			'feed_id'=>$feed_id,
			'create_time'=>\F::app()->current_time,
			'trackid'=>$trackid,
			'sockpuppet'=>$sockpuppet,
		));
		
		//动态收藏数+1
		if($sockpuppet){
			//非真实用户行为
			FeedMeta::model()->incr($feed_id, array('favorites'), 1);
		}else{
			//真实用户行为
			FeedMeta::model()->incr($feed_id, array('favorites', 'real_favorites'), 1);
		}
		
		Hook::getInstance()->call('after_feed_favorite');
	}
	
	/**
	 * 取消收藏
	 * @param int $feed_id 动态ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function unfavorite($feed_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		$favorite = FeedFavorites::model()->find(array($feed_id, $user_id), 'sockpuppet');
		if($favorite){
			//删除点赞关系
			FeedFavorites::model()->delete(array(
				'user_id = ?'=>$user_id,
				'feed_id = ?'=>$feed_id,
			));
				
			if($favorite['sockpuppet']){
				//非真实用户行为
				FeedMeta::model()->incr($feed_id, array('favorites'), -1);
			}else{
				//真实用户行为
				FeedMeta::model()->incr($feed_id, array('favorites', 'favorites'), -1);
			}
				
			//执行钩子
			Hook::getInstance()->call('after_feed_unfavorite');
				
			return true;
		}else{
			//未点赞
			return false;
		}
		
		//删除收藏关系
		FeedFavorites::model()->delete(array(
			'user_id = ?'=>$user_id,
			'feed_id = ?'=>$feed_id,
		));
		
		Hook::getInstance()->call('after_feed_unfavorite');
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
		
		if(FeedFavorites::model()->find(array($user_id, $feed_id))){
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