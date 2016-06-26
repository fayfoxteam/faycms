<?php
namespace fay\services\feed;

use fay\core\Service;
use fay\core\Hook;
use fay\core\Exception;
use fay\models\User;
use fay\models\Feed;
use fay\models\tables\FeedFavorites;
use fay\models\tables\FeedMeta;
use fay\models\feed\Favorite as FavoriteModel;
use fay\helpers\Request;

class Favorite extends Service{
	/**
	 * @return Favorite
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 收藏动态
	 * @param int $feed_id 动态ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function add($feed_id, $trackid = '', $user_id = null, $sockpuppet = 0){
		if($user_id === null){
			$user_id = \F::app()->current_user;
		}else if(!User::isUserIdExist($user_id)){
			throw new Exception('指定用户ID不存在', 'the-given-user-id-is-not-exist');
		}
		
		if(!Feed::isFeedIdExist($feed_id)){
			throw new Exception('指定的动态ID不存在', 'the-given-feed-id-is-not-exist');
		}
		
		if(FavoriteModel::isFavorited($feed_id, $user_id)){
			throw new Exception('已收藏，不能重复收藏', 'already-favorited');
		}
		
		FeedFavorites::model()->insert(array(
			'user_id'=>$user_id,
			'feed_id'=>$feed_id,
			'trackid'=>$trackid,
			'sockpuppet'=>$sockpuppet,
			'create_time'=>\F::app()->current_time,
			'ip_int'=>Request::ip2int(\F::app()->ip),
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
	public static function remove($feed_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		$favorite = FeedFavorites::model()->find(array($user_id, $feed_id), 'sockpuppet');
		if($favorite){
			//删除收藏关系
			FeedFavorites::model()->delete(array(
				'user_id = ?'=>$user_id,
				'feed_id = ?'=>$feed_id,
			));
			
			//动态收藏数-1
			if($favorite['sockpuppet']){
				//非真实用户行为
				FeedMeta::model()->incr($feed_id, array('favorites'), -1);
			}else{
				//真实用户行为
				FeedMeta::model()->incr($feed_id, array('favorites', 'real_favorites'), -1);
			}
				
			//执行钩子
			Hook::getInstance()->call('after_feed_unfavorite');
				
			return true;
		}else{
			//未点赞
			return false;
		}
	}
}