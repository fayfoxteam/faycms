<?php
namespace guangong\models\tables;

use fay\core\db\Table;

/**
 * 时辰表
 *
 * @property int $id Id
 * @property string $name 名称
 * @property int $start_hour 开始小时（一个时辰包含2个小时）
 * @property int $end_hour 结束小时（一个时辰包含2个小时）
 * @property string $description 描述
 * @property string $zodiac 生肖
 */
class GuangongHoursTable extends Table{
	protected $_name = 'guangong_hours';
	
	/**
	 * @param string $class_name
	 * @return GuangongHoursTable
	 
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'start_hour', 'end_hour'), 'int', array('min'=>0, 'max'=>255)),
			array(array('name'), 'string', array('max'=>10)),
			array(array('description', 'zodiac'), 'string', array('max'=>500)),
		);
	}
	
	public function labels(){
		return array(
			'id'=>'Id',
			'name'=>'名称',
			'start_hour'=>'开始小时（一个时辰包含2个小时）',
			'end_hour'=>'结束小时（一个时辰包含2个小时）',
			'description'=>'描述',
			'zodiac'=>'生肖',
		);
	}
	
	public function filters(){
		return array(
			'id'=>'intval',
			'name'=>'trim',
			'start_hour'=>'intval',
			'end_hour'=>'intval',
			'description'=>'trim',
			'zodiac'=>'trim',
		);
	}
}