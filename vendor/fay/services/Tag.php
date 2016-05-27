<?php
namespace fay\services;

use fay\core\Model;
use fay\models\Tag as TagModel;
use fay\models\tables\Tags;
use fay\models\tables\TagCounter;

/**
 * 标签服务
 */
class Tag extends Model{
	/**
	 * @param string $class_name
	 * @return Post
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 创建一个标签，并返回标签ID
	 * @param string $title 标签
	 * @return int 标签ID
	 */
	public function create($title){
		//判断标签是否存在，若已存在，直接返回标签ID
		$tag = TagModel::isTagExist($title);
		if($tag){
			return $tag;
		}
		
		$tag_id = Tags::model()->insert(array(
			'title'=>$title,
			'user_id'=>\F::app()->current_user,
			'create_time'=>\F::app()->current_time,
		));
		
		TagCounter::model()->insert(array(
			'tag_id'=>$tag_id,
		));
		
		return $tag_id;
	}
}