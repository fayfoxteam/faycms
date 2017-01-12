<?php
namespace guangong\models\tables;

use fay\core\db\Table;

/**
 * Guangong user extra table model
 *
 * @property int $user_id 用户ID
 * @property string $birthday 生日
 * @property int $state 省
 * @property int $city 市
 * @property int $district 区/县
 * @property int $arm_id 兵种
 * @property int $defence_area_id 防区ID
 * @property int $attendances 总出勤次数
 */
class UserExtraTable extends Table{
	protected $_name = 'guangong_user_extra';
	protected $_primary = 'user_id';
	
	/**
	 * @param string $class_name
	 * @return UserExtraTable
	 
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('state', 'city', 'district', 'defence_area_id', 'attendances'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('arm_id'), 'int', array('min'=>0, 'max'=>255)),
		);
	}
	
	public function labels(){
		return array(
			'user_id'=>'用户ID',
			'birthday'=>'生日',
			'state'=>'省',
			'city'=>'市',
			'district'=>'区/县',
			'arm_id'=>'兵种',
			'defence_area_id'=>'防区ID',
			'attendances'=>'总出勤次数',
		);
	}
	
	public function filters(){
		return array(
			'user_id'=>'intval',
			'birthday'=>'',
			'state'=>'intval',
			'city'=>'intval',
			'district'=>'intval',
			'arm_id'=>'intval',
			'defence_area_id'=>'intval',
			'attendances'=>'intval',
		);
	}
}