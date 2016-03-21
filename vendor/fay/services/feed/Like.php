<?php
namespace fay\services\feed;

use fay\core\Model;
use fay\core\Hook;
use fay\core\Exception;
use fay\models\tables\FeedLikes;
use fay\models\User;
use fay\models\Feed;
use fay\models\tables\FeedMeta;
use fay\models\feed\Like as LikeModel;

class Like extends Model{
	/**
	 * @return Like
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 给动态点赞
	 * @param int $feed_id 动态ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @param bool $sockpuppet 马甲信息
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
		
		if(LikeModel::isLiked($feed_id, $user_id)){
			throw new Exception('已赞过，不能重复点赞', 'already-liked');
		}
		
		FeedLikes::model()->insert(array(
			'feed_id'=>$feed_id,
			'user_id'=>$user_id,
			'create_time'=>\F::app()->current_time,
			'trackid'=>$trackid,
			'sockpuppet'=>$sockpuppet,
		));
		
		//动态点赞数+1
		if($sockpuppet){
			//非真实用户行为
			FeedMeta::model()->incr($feed_id, array('likes'), 1);
		}else{
			//真实用户行为
			FeedMeta::model()->incr($feed_id, array('likes', 'real_likes'), 1);
		}
		
		//执行钩子
		Hook::getInstance()->call('after_feed_like');
	}
	
	/**
	 * 取消点赞
	 * @param int $feed_id 动态ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 */
	public static function remove($feed_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		$like = FeedLikes::model()->find(array($feed_id, $user_id), 'sockpuppet');
		if($like){
			//删除点赞关系
			FeedLikes::model()->delete(array(
				'user_id = ?'=>$user_id,
				'feed_id = ?'=>$feed_id,
			));
			
			if($like['sockpuppet']){
				//非真实用户行为
				FeedMeta::model()->incr($feed_id, array('likes'), -1);
			}else{
				//真实用户行为
				FeedMeta::model()->incr($feed_id, array('likes', 'likes'), -1);
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