<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Feed Meta model
 * 
 * @property int $feed_id 动态ID
 * @property int $comments 评论数
 * @property int $real_comments 真实评论数
 * @property int $likes 点赞数
 * @property int $real_likes 真实点赞数
 */
class FeedMeta extends Table{
	protected $_name = 'feed_meta';
	protected $_primary = 'feed_id';
	
	/**
	 * @return FeedMeta
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('feed_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('likes', 'real_likes'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('comments', 'real_comments'), 'int', array('min'=>0, 'max'=>65535)),
		);
	}

	public function labels(){
		return array(
			'feed_id'=>'动态ID',
			'comments'=>'评论数',
			'real_comments'=>'真实评论数',
			'likes'=>'点赞数',
			'real_likes'=>'真实点赞数',
		);
	}

	public function filters(){
		return array(
			'feed_id'=>'intval',
			'comments'=>'intval',
			'real_comments'=>'intval',
			'likes'=>'intval',
			'real_likes'=>'intval',
		);
	}
}