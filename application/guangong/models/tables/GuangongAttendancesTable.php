<?php
namespace guangong\models\tables;

use fay\core\db\Table;

/**
 * 出勤记录表
 * 
 * @property int $id Id
 * @property int $user_id 用户ID
 * @property int $create_time 出勤时间
 */
class GuangongAttendancesTable extends Table{
	protected $_name = 'guangong_attendances';
	
	/**
	 * @param string $class_name
	 * @return GuangongAttendancesTable

	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('user_id'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('id'), 'int', array('min'=>0, 'max'=>4294967295)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'user_id'=>'用户ID',
			'create_time'=>'出勤时间',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'user_id'=>'intval',
		);
	}
}