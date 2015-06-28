<?php
namespace fay\models\tables;

use fay\core\db\Table;

class Followers extends Table{
	protected $_name = 'followers';
	protected $_primary = array('user_id', 'follower');
	
	/**
	 * @return Followers
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('user_id', 'follower', 'create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
		);
	}

	public function labels(){
		return array(
			'user_id'=>'User Id',
			'follower'=>'Follower',
			'create_time'=>'Create Time',
		);
	}

	public function filters(){
		return array(
			'user_id'=>'intval',
			'follower'=>'intval',
			'create_time'=>'',
		);
	}
}