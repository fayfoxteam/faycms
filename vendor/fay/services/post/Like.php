<?php
namespace fay\services\post;

use fay\common\ListView;
use fay\core\Service;
use fay\core\Hook;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use fay\models\tables\PostLikes;
use fay\models\tables\Posts;
use fay\services\User;
use fay\services\Post;
use fay\models\tables\PostMeta;

class Like extends Service{
	/**
	 * @param string $class_name
	 * @return Like
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
	 * @throws Exception
	 */
	public static function add($post_id, $trackid = '', $user_id = null, $sockpuppet = 0){
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
			'trackid'=>$trackid,
			'sockpuppet'=>$sockpuppet,
		));
		
		//文章点赞数+1
		if($sockpuppet){
			//非真实用户行为
			PostMeta::model()->incr($post_id, array('likes'), 1);
		}else{
			//真实用户行为
			PostMeta::model()->incr($post_id, array('likes', 'real_likes'), 1);
		}
		
		//执行钩子
		Hook::getInstance()->call('after_post_like');
	}
	
	/**
	 * 取消点赞
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @return bool
	 * @throws Exception
	 */
	public static function remove($post_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		$like = PostLikes::model()->find(array($post_id, $user_id), 'sockpuppet');
		if($like){
			//删除点赞关系
			PostLikes::model()->delete(array(
				'user_id = ?'=>$user_id,
				'post_id = ?'=>$post_id,
			));
				
			if($like['sockpuppet']){
				//非真实用户行为
				PostMeta::model()->incr($post_id, array('likes'), -1);
			}else{
				//真实用户行为
				PostMeta::model()->incr($post_id, array('likes', 'real_likes'), -1);
			}
				
			//执行钩子
			Hook::getInstance()->call('after_feed_unlike');
				
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
	 * @throws Exception
	 */
	public static function isLiked($post_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(PostLikes::model()->find(array($post_id, $user_id), 'create_time')){
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
	 * @throws Exception
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
			->where(Posts::getPublishedConditions('p'))
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
			'likes'=>User::service()->mget(ArrayHelper::column($likes, 'user_id'), $fields),
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
	 * @throws Exception
	 */
	public function getUserLikes($fields, $page = 1, $page_size = 20, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		$sql = new Sql();
		$sql->from(array('pl'=>'post_likes'), 'post_id')
			->joinLeft(array('p'=>'posts'), 'pl.post_id = p.id')
			->where('pl.user_id = ?', $user_id)
			->where(Posts::getPublishedConditions('p'))
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
			'likes'=>array_values(Post::service()->mget(
				ArrayHelper::column($likes, 'post_id'),
				$fields
			)),
			'pager'=>$listview->getPager(),
		);
	}
}