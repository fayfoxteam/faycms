<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Feeds;
use fay\helpers\FieldHelper;

class Feed extends Model{
	/**
	 * 允许在接口调用时返回的字段
	 */
	public static $public_fields = array(
		'feed'=>array(
			'id', 'content', 'publish_time', 'address'
		),
		'category'=>array(
			'id', 'title', 'alias',
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
		'tags'=>array(
			'id', 'title',
		),
		'files'=>array(
			'file_id', 'description',
		),
		'meta'=>array(
			'comments', 'likes', 'favorites'
		),
	);
	
	/**
	 * 默认接口返回字段
	 */
	public static $default_fields = array(
		'feed'=>array(
			'id', 'content', 'publish_time', 'address'
		),
		'user'=>array(
			'id', 'nickname', 'avatar',
		),
		'files'=>array(
			'file_id', 'description',
		),
		'meta'=>array(
			'comments', 'likes', 'favorites'
		),
	);
	
	/**
	 * @return Feed
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 判断一个动态ID是否存在（“已删除/未发布/未到定时发布时间”的动态都被视为不存在）
	 * @param int $feed_id
	 * @return bool 若动态已发布且未删除返回true，否则返回false
	 */
	public static function isFeedIdExist($feed_id){
		if($feed_id){
			$feed = Feeds::model()->find($feed_id, 'deleted,publish_time,status');
			if($feed['deleted'] || $feed['publish_time'] > \F::app()->current_time || $feed['status'] != Feeds::STATUS_PUBLISHED){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}
	
	public function get($id, $fields){
		
	}
	
	public function mget($ids, $fields){
		//解析$fields
		$fields = FieldHelper::process($fields, 'feed');
		if(empty($fields['feed']) || in_array('*', $fields['feed'])){
			//若未指定返回字段，初始化（默认不返回content，因为列表页基本是不会显示文章详情的）
			$fields['feed'] = Feeds::model()->getFields();
		}
		
		$feed_fields = $fields['feed'];
		if(!empty($fields['user']) && !in_array('user_id', $feed_fields)){
			//如果要获取作者信息，则必须搜出user_id
			$feed_fields[] = 'user_id';
		}
		if(!empty($fields['category']) && !in_array('cat_id', $feed_fields)){
			//如果要获取作者信息，则必须搜出user_id
			$feed_fields[] = 'cat_id';
		}
		if(!in_array('id', $fields['feed'])){
			//id字段无论如何都要返回，因为后面要用到
			$feed_fields[] = 'id';
		}
	}
}