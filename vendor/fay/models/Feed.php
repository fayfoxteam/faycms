<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Feeds;

class Feed extends Model{
	/**
	 * @return Feed
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 判断一个文章ID是否存在（“已删除/未发布/未到定时发布时间”的文章都被视为不存在）
	 * @param int $post_id
	 * @return bool 若文章已发布且未删除返回true，否则返回false
	 */
	public static function isFeedIdExist($post_id){
		if($post_id){
			$post = Feeds::model()->find($post_id, 'deleted,publish_time,status');
			if($post['deleted'] || $post['publish_time'] > \F::app()->current_time || $post['status'] != Feeds::STATUS_PUBLISHED){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}
}