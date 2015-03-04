<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Likes extends Table{
	protected $_name = 'likes';
	protected $_primary = array('post_id', 'user_id');
	
	/**
	 * @return Likes
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('post_id', 'user_id', 'create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
		);
	}

	public function labels(){
		return array(
			'post_id'=>'Post Id',
			'user_id'=>'User Id',
			'create_time'=>'Create Time',
		);
	}

	public function filters(){
		return array(
			'post_id'=>'intval',
			'user_id'=>'intval',
			'create_time'=>'',
		);
	}
}