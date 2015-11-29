<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Favourites extends Table{
	protected $_name = 'favourites';
	protected $_primary = array('user_id', 'post_id');
	
	/**
	 * @return Favourites
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('user_id', 'post_id', 'create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
		);
	}

	public function labels(){
		return array(
			'user_id'=>'User Id',
			'post_id'=>'Post Id',
			'create_time'=>'Create Time',
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