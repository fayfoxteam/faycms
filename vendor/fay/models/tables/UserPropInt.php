<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * User Prop Int model
 * 
 * @property int $user_id
 * @property int $prop_id
 * @property int $content
 */
class UserPropInt extends Table{
	protected $_name = 'user_prop_int';
	protected $_primary = array('user_id', 'prop_id', 'content');
	
	/**
	 * @param string $class_name
	 * @return UserPropInt
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('user_id', 'prop_id', 'content'), 'int', array('min'=>0, 'max'=>4294967295)),
		);
	}

	public function labels(){
		return array(
			'user_id'=>'用户ID',
			'prop_id'=>'角色ID',
			'content'=>'角色值',
		);
	}

	public function filters(){
		return array(
			'user_id'=>'intval',
			'prop_id'=>'intval',
			'content'=>'intval',
		);
	}
}