<?php
namespace fay\services\feed;

use fay\core\Service;
use fay\core\Exception;
use fay\models\tables\FeedLikes;
use fay\services\User;
use fay\models\Feed;
use fay\models\tables\FeedMeta;

class Like extends Service{
	/**
	 * @param string $class_name
	 * @return Like
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 给动态点赞
	 * @param int $feed_id 动态ID
	 * @param string $trackid
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @param bool|int $sockpuppet 马甲信息
	 * @throws Exception
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
		
		if(self::isLiked($feed_id, $user_id)){
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
		\F::event()->trigger('after_feed_like');
	}
	
	/**
	 * 取消点赞
	 * @param int $feed_id 动态ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @return bool
	 * @throws Exception
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
				FeedMeta::model()->incr($feed_id, array('likes', 'real_likes'), -1);
			}
			
			//执行钩子
			\F::event()->trigger('after_feed_unlike');
			
			return true;
		}else{
			//未点赞
			return false;
		}
	}
	
	/**
	 * 判断是否赞过
	 * @param int $feed_id 动态ID
	 * @param int|null $user_id 用户ID，默认为当前登录用户
	 * @return bool
	 * @throws Exception
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
	 * @return array
	 * @throws Exception
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