<?php
namespace fay\models\tables;

use fay\core\db\Table;

class ProfileText extends Table{
	protected $_name = 'profile_text';
	protected $_primary = array('user_id', 'prop_id');
	
	/**
	 * @return ProfileText
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('user_id', 'prop_id'), 'int', array('min'=>0, 'max'=>4294967295)),
		);
	}

	public function labels(){
		return array(
			'user_id'=>'User Id',
			'prop_id'=>'Prop Id',
			'content'=>'Content',
		);
	}

	public function filters(){
		return array(
			'user_id'=>'intval',
			'prop_id'=>'intval',
			'content'=>'',
		);
	}
}