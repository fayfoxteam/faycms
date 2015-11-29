<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Post Favorites model
 * 
 * @property int $user_id 用户ID
 * @property int $post_id 文章ID
 * @property int $create_time 收藏时间
 */
class PostFavorites extends Table{
	protected $_name = 'post_favorites';
	protected $_primary = array('user_id', 'post_id');
	
	/**
	 * @return PostFavorites
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('user_id', 'post_id'), 'int', array('min'=>0, 'max'=>4294967295)),
		);
	}

	public function labels(){
		return array(
			'user_id'=>'用户ID',
			'post_id'=>'文章ID',
			'create_time'=>'收藏时间',
		);
	}

	public function filters(){
		return array(
			'user_id'=>'intval',
			'post_id'=>'intval',
			'create_time'=>'',
		);
	}
}