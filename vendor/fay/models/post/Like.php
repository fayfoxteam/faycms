<?php
namespace fay\models\post;

use fay\core\Model;
use fay\core\Exception;
use fay\models\tables\PostLikes;
use fay\helpers\ArrayHelper;
use fay\core\Sql;
use fay\models\tables\Posts;
use fay\common\ListView;
use fay\models\Post;
use fay\models\User;

class Like extends Model{
	/**
	 * @param string $class_name
	 * @return Like
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
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
	 * @internal param int $user_id 用户ID，默认为当前登录用户
	 */
	public function getPostLikes($post_id, $fields, $page = 1, $page_size = 20){
		$sql = new Sql();
		$sql->from(array('pl'=>'post_likes'), 'user_id')
			->joinLeft(array('p'=>'posts'), 'pl.post_id = p.id')
			->where('pl.post_id = ?', $post_id)
			->where(array(
				'deleted = 0',
				'publish_time < '.\F::app()->current_time,
				'status = '.Posts::STATUS_PUBLISHED,
			))
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
			'likes'=>User::model()->mget(ArrayHelper::column($likes, 'user_id'), $fields),
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
			->where(array(
				'deleted = 0',
				'publish_time < '.\F::app()->current_time,
				'status = '.Posts::STATUS_PUBLISHED,
			))
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
			'likes'=>Post::model()->mget(ArrayHelper::column($likes, 'post_id'), $fields),
			'pager'=>$listview->getPager(),
		);
	}
}