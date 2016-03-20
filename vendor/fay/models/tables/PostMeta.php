<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Post Meta model
 * 
 * @property int $post_id 文章ID
 * @property int $last_view_time 最后访问时间
 * @property int $views 阅读数
 * @property int $real_views 真实阅读数
 * @property int $comments 评论数
 * @property int $real_comments 真实评论数
 * @property int $likes 点赞数
 * @property int $real_likes 真实点赞数
 */
class PostMeta extends Table{
	protected $_name = 'post_meta';
	protected $_primary = 'post_id';
	
	/**
	 * @return PostMeta
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('post_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('views', 'real_views', 'likes', 'real_likes', 'favorites', 'real_favorites'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('comments', 'real_comments'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('last_view_time'), 'datetime'),
		);
	}

	public function labels(){
		return array(
			'post_id'=>'文章ID',
			'last_view_time'=>'最后访问时间',
			'views'=>'阅读数',
			'real_views'=>'真实阅读数',
			'comments'=>'评论数',
			'real_comments'=>'真实评论数',
			'likes'=>'点赞数',
			'real_likes'=>'真实点赞数',
			'favorites'=>'收藏数',
			'real_favorites'=>'真实收藏数',
		);
	}

	public function filters(){
		return array(
			'post_id'=>'intval',
			'views'=>'intval',
			'comments'=>'intval',
			'likes'=>'intval',
		);
	}
}