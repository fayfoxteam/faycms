<?php
namespace valentine\models\tables;

use fay\core\db\Table;

/**
 * 用户扩展信息
 * 
 * @property int $user_id User Id
 * @property int $age 年龄
 * @property int $constellation_id 星座
 * @property string $birthday 生日
 */
class ValentineUserExtraTable extends Table{
	protected $_name = 'valentine_user_extra';
	protected $_primary = 'user_id';
	
	/**
	 * @param string $class_name
	 * @return ValentineUserExtraTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('age', 'constellation_id'), 'int', array('min'=>0, 'max'=>255)),
		);
	}

	public function labels(){
		return array(
			'user_id'=>'User Id',
			'age'=>'年龄',
			'constellation_id'=>'星座',
			'birthday'=>'生日',
		);
	}

	public function filters(){
		return array(
			'user_id'=>'intval',
			'age'=>'intval',
			'constellation_id'=>'intval',
			'birthday'=>'',
		);
	}
}