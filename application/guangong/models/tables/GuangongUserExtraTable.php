<?php
namespace guangong\models\tables;

use fay\core\db\Table;

/**
 * 用户扩展信息
 *
 * @property int $user_id 用户ID
 * @property string $birthday 生日
 * @property int $state 省
 * @property int $city 市
 * @property int $district 区/县
 * @property int $arm_id 兵种
 * @property int $defence_area_id 防区ID
 * @property int $hour_id 时辰ID
 * @property int $attendances 总出勤次数
 * @property int $rank_id 军衔ID
 * @property int $military 缴纳军费（单位：分）
 * @property int $sign_up_time 报名时间
 */
class GuangongUserExtraTable extends Table{
	protected $_name = 'guangong_user_extra';
	protected $_primary = 'user_id';
	
	/**
	 * @param string $class_name
	 * @return GuangongUserExtraTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('user_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('state', 'city', 'district', 'defence_area_id', 'attendances', 'rank_id', 'military'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('arm_id', 'hour_id'), 'int', array('min'=>0, 'max'=>255)),
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
			'hour_id'=>'时辰ID',
			'attendances'=>'总出勤次数',
			'rank_id'=>'军衔ID',
			'military'=>'缴纳军费（单位：分）',
			'sign_up_time'=>'报名时间',
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
			'hour_id'=>'intval',
			'attendances'=>'intval',
			'rank_id'=>'intval',
			'military'=>'intval',
		);
	}
}