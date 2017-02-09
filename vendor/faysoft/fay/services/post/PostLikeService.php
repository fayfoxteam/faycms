<?php
namespace fay\services\post;

use fay\common\ListView;
use fay\core\Service;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use fay\models\tables\PostLikesTable;
use fay\models\tables\PostsTable;
use fay\services\user\UserService;
use fay\models\tables\PostMetaTable;

class PostLikeService extends Service{
	/**
	 * 文章被点赞后事件
	 */
	const EVENT_LIKE = 'after_post_like';
	
	/**
	 * 文章被取消点赞后事件
	 */
	const EVENT_UNLIKE = 'after_post_unlike';
	
	/**
	 * @param string $class_name
	 * @return PostLikeService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 给文章点赞
	 * @param int $post_id 文章ID
	 * @param string $trackid
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @param bool|int $sockpuppet 马甲信息
	 * @throws PostException
	 */
	public static function add($post_id, $trackid = '', $user_id = null, $sockpuppet = 0){
		if($user_id === null){
			$user_id = \F::app()->current_user;
		}else if(!UserService::isUserIdExist($user_id)){
			throw new PostException('指定用户ID不存在', 'the-given-user-id-is-not-exist');
		}
		
		if(!PostService::isPostIdExist($post_id)){
			throw new PostException('指定的文章ID不存在', 'the-given-post-id-is-not-exist');
		}
		
		if(self::isLiked($post_id, $user_id)){
			throw new PostException('已赞过，不能重复点赞', 'already-liked');
		}
		
		PostLikesTable::model()->insert(array(
			'post_id'=>$post_id,
			'user_id'=>$user_id,
			'create_time'=>\F::app()->current_time,
			'trackid'=>$trackid,
			'sockpuppet'=>$sockpuppet,
		));
		
		//文章点赞数+1
		if($sockpuppet){
			//非真实用户行为
			PostMetaTable::model()->incr($post_id, array('likes'), 1);
		}else{
			//真实用户行为
			PostMetaTable::model()->incr($post_id, array('likes', 'real_likes'), 1);
		}
		
		//触发事件
		\F::event()->trigger(self::EVENT_LIKE, $post_id);
	}
	
	/**
	 * 取消点赞
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @return bool
	 * @throws PostException
	 */
	public static function remove($post_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new PostException('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		$like = PostLikesTable::model()->find(array($post_id, $user_id), 'sockpuppet');
		if($like){
			//删除点赞关系
			PostLikesTable::model()->delete(array(
				'user_id = ?'=>$user_id,
				'post_id = ?'=>$post_id,
			));
				
			if($like['sockpuppet']){
				//非真实用户行为
				PostMetaTable::model()->incr($post_id, array('likes'), -1);
			}else{
				//真实用户行为
				PostMetaTable::model()->incr($post_id, array('likes', 'real_likes'), -1);
			}
				
			//触发事件
			\F::event()->trigger(self::EVENT_UNLIKE, $post_id);
				
			return true;
		}else{
			//未点赞
			return false;
		}
	}
	
	/**
	 * 判断是否赞过
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @return bool
	 * @throws PostException
	 */
	public static function isLiked($post_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new PostException('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(PostLikesTable::model()->find(array($post_id, $user_id), 'create_time')){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 批量判断是否赞过
	 * @param array $post_ids 由文章ID组成的一维数组
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @return array
	 * @throws PostException
	 */
	public static function mIsLiked($post_ids, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new PostException('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(!is_array($post_ids)){
			$post_ids = explode(',', str_replace(' ', '', $post_ids));
		}
		
		$likes = PostLikesTable::model()->fetchAll(array(
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
	
	/**
	 * 获取文章点赞列表
	 * @param $post_id
	 * @param array|string $fields 用户字段
	 * @param int $page
	 * @param int $page_size
	 * @return array
	 */
	public function getPostLikes($post_id, $fields, $page = 1, $page_size = 20){
		$sql = new Sql();
		$sql->from(array('pl'=>'post_likes'), 'user_id')
			->joinLeft(array('p'=>'posts'), 'pl.post_id = p.id')
			->where('pl.post_id = ?', $post_id)
			->where(PostsTable::getPublishedConditions('p'))
			->order('pl.create_time DESC')
		;
		
		$listview = new ListView($sql, array(
			'page_size'=>$page_size,
			'current_page'=>$page,
		));
		
		$likes = $listview->getData();
		
		if(!$likes){
			return array();
		}
		
		return array(
			'likes'=>UserService::service()->mget(ArrayHelper::column($likes, 'user_id'), $fields),
			'pager'=>$listview->getPager(),
		);
	}
	
	/**
	 * 获取用户点赞列表
	 * @param string $fields 文章字段
	 * @param int $page
	 * @param int $page_size
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @return array
	 * @throws PostException
	 */
	public function getUserLikes($fields, $page = 1, $page_size = 20, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new PostException('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		$sql = new Sql();
		$sql->from(array('pl'=>'post_likes'), 'post_id')
			->joinLeft(array('p'=>'posts'), 'pl.post_id = p.id')
			->where('pl.user_id = ?', $user_id)
			->where(PostsTable::getPublishedConditions('p'))
			->order('pl.create_time DESC')
		;
		
		$listview = new ListView($sql, array(
			'page_size'=>$page_size,
			'current_page'=>$page,
		));
		
		$likes = $listview->getData();
		
		if(!$likes){
			return array();
		}
		
		return array(
			'likes'=>PostService::service()->mget(
				ArrayHelper::column($likes, 'post_id'),
				$fields
			),
			'pager'=>$listview->getPager(),
		);
	}
}