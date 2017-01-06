<?php
namespace fay\services\post;

use fay\common\ListView;
use fay\core\Service;
use fay\core\Sql;
use fay\helpers\ArrayHelper;
use fay\models\tables\Posts;
use fay\services\UserService;
use fay\services\PostService;
use fay\models\tables\PostFavorites;
use fay\helpers\Request;
use fay\models\tables\PostMeta;

class PostFavoriteService extends Service{
	/**
	 * 文章被收藏后事件
	 */
	const EVENT_FAVORITED = 'after_post_favorite';
	
	/**
	 * 文章被取消收藏后事件
	 */
	const EVENT_CANCEL_FAVORITED = 'after_post_cancel_favorite';
	
	/**
	 * @param string $class_name
	 * @return PostFavoriteService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 收藏文章
	 * @param int $post_id 文章ID
	 * @param string $trackid
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @param int $sockpuppet
	 * @throws Exception
	 */
	public static function add($post_id, $trackid = '', $user_id = null, $sockpuppet = 0){
		if($user_id === null){
			$user_id = \F::app()->current_user;
		}else if(!UserService::isUserIdExist($user_id)){
			throw new Exception('指定用户ID不存在', 'the-given-user-id-is-not-exist');
		}
		
		if(!PostService::isPostIdExist($post_id)){
			throw new Exception('指定的文章ID不存在', 'the-given-post-id-is-not-exist');
		}
		
		if(self::isFavorited($post_id, $user_id)){
			throw new Exception('已收藏，不能重复收藏', 'already-favorited');
		}
		
		PostFavorites::model()->insert(array(
			'user_id'=>$user_id,
			'post_id'=>$post_id,
			'trackid'=>$trackid,
			'sockpuppet'=>$sockpuppet,
			'create_time'=>\F::app()->current_time,
			'ip_int'=>Request::ip2int(\F::app()->ip),
		));
		
		//文章收藏数+1
		if($sockpuppet){
			//非真实用户行为
			PostMeta::model()->incr($post_id, array('favorites'), 1);
		}else{
			//真实用户行为
			PostMeta::model()->incr($post_id, array('favorites', 'real_favorites'), 1);
		}
		
		\F::event()->trigger(self::EVENT_FAVORITED, $post_id);
	}
	
	/**
	 * 取消收藏
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
		
		$favorite = PostFavorites::model()->find(array($user_id, $post_id), 'sockpuppet');
		if($favorite){
			//删除收藏关系
			PostFavorites::model()->delete(array(
				'user_id = ?'=>$user_id,
				'post_id = ?'=>$post_id,
			));
			
			//文章收藏数-1
			if($favorite['sockpuppet']){
				//非真实用户行为
				PostMeta::model()->incr($post_id, array('favorites'), -1);
			}else{
				//真实用户行为
				PostMeta::model()->incr($post_id, array('favorites', 'favorites'), -1);
			}
				
			//触发事件
			\F::event()->trigger(self::EVENT_CANCEL_FAVORITED, $post_id);
				
			return true;
		}else{
			//未点赞
			return false;
		}
	}
	
	/**
	 * 判断是否收藏过
	 * @param int $post_id 文章ID
	 * @param int $user_id 用户ID，默认为当前登录用户
	 * @return bool
	 * @throws Exception
	 */
	public static function isFavorited($post_id, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(PostFavorites::model()->find(array($user_id, $post_id), 'create_time')){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 批量判断是否收藏过
	 * @param array $post_ids 由文章ID组成的一维数组
	 * @param int|null $user_id 用户ID，默认为当前登录用户
	 * @return array
	 * @throws Exception
	 */
	public static function mIsFavorited($post_ids, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		if(!is_array($post_ids)){
			$post_ids = explode(',', str_replace(' ', '', $post_ids));
		}
		
		$favorites = PostFavorites::model()->fetchAll(array(
			'user_id = ?'=>$user_id,
			'post_id IN (?)'=>$post_ids,
		), 'post_id');
		
		$favorite_map = ArrayHelper::column($favorites, 'post_id');
		
		$return = array();
		foreach($post_ids as $p){
			$return[$p] = in_array($p, $favorite_map);
		}
		return $return;
	}
	
	/**
	 * 获取收藏列表
	 * @param string $fields 文章字段
	 * @param int $page
	 * @param int $page_size
	 * @param int|null $user_id 用户ID，默认为当前登录用户
	 * @return array
	 * @throws Exception
	 */
	public function getList($fields, $page = 1, $page_size = 20, $user_id = null){
		$user_id || $user_id = \F::app()->current_user;
		if(!$user_id){
			throw new Exception('未能获取到用户ID', 'can-not-find-a-effective-user-id');
		}
		
		$sql = new Sql();
		$sql->from(array('pf'=>'post_favorites'), 'post_id')
			->joinLeft(array('p'=>'posts'), 'pf.post_id = p.id')
			->where('pf.user_id = ?', $user_id)
			->where(Posts::getPublishedConditions('p'))
			->order('pf.create_time DESC')
		;
		
		$listview = new ListView($sql, array(
			'page_size'=>$page_size,
			'current_page'=>$page,
		));
		
		$favorites = $listview->getData();
		
		if(!$favorites){
			return array();
		}
		
		return array(
			'favorites'=>PostService::service()->mget(
				ArrayHelper::column($favorites, 'post_id'),
				$fields
			),
			'pager'=>$listview->getPager(),
		);
	}
}