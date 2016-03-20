<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Feed Favorites model
 * 
 * @property int $feed_id 动态ID
 * @property int $user_id 用户ID
 * @property int $create_time 收藏时间
 * @property int $ip_int IP
 * @property int $sockpuppet 马甲信息
 * @property string $trackid 追踪ID
 */
class FeedFavorites extends Table{
	protected $_name = 'feed_favorites';
	protected $_primary = array('user_id', 'feed_id');
	
	/**
	 * @return FeedFavorites
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('user_id', 'feed_id', 'sockpuppet'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('trackid'), 'string', array('max'=>50)),
		);
	}

	public function labels(){
		return array(
			'user_id'=>'用户ID',
			'feed_id'=>'动态ID',
			'create_time'=>'收藏时间',
			'ip_int'=>'IP',
			'sockpuppet'=>'马甲信息',
			'trackid'=>'追踪ID',
		);
	}

	public function filters(){
		return array(
			'user_id'=>'intval',
			'feed_id'=>'intval',
			'sockpuppet'=>'intval',
			'trackid'=>'trim',
		);
	}
}