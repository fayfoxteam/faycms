<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Post Likes model
 * 
 * @property int $post_id 文章ID
 * @property int $user_id 用户ID
 * @property int $create_time 点赞时间
 * @property int $is_real 是否真实用户
 */
class PostLikes extends Table{
	protected $_name = 'post_likes';
	protected $_primary = array('post_id', 'user_id');
	
	/**
	 * @return PostLikes
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('post_id', 'user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('is_real'), 'range', array('range'=>array(0, 1))),
		);
	}

	public function labels(){
		return array(
			'post_id'=>'文章ID',
			'user_id'=>'用户ID',
			'create_time'=>'点赞时间',
			'is_real'=>'是否真实用户',
		);
	}

	public function filters(){
		return array(
			'post_id'=>'intval',
			'user_id'=>'intval',
			'create_time'=>'',
			'is_real'=>'intval',
		);
	}
}