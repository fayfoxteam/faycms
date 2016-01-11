<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Post Likes model
 * 
 * @property int $post_id 文章ID
 * @property int $user_id 用户ID
 * @property int $create_time 点赞时间
 * @property int $sockpuppet 马甲信息
 */
class PostLikes extends Table{
	protected $_name = 'post_likes';
	protected $_primary = array('post_id', 'user_id');
	
	/**
	 * @return PostLikes
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('post_id', 'user_id', 'sockpuppet'), 'int', array('min'=>0, 'max'=>4294967295)),
		);
	}

	public function labels(){
		return array(
			'post_id'=>'文章ID',
			'user_id'=>'用户ID',
			'create_time'=>'点赞时间',
			'sockpuppet'=>'马甲信息',
		);
	}

	public function filters(){
		return array(
			'post_id'=>'intval',
			'user_id'=>'intval',
			'create_time'=>'',
			'sockpuppet'=>'intval',
		);
	}
}