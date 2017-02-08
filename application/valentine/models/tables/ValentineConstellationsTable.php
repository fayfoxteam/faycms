<?php
namespace valentine\models\tables;

use fay\core\db\Table;

/**
 * 星座表
 * 
 * @property int $id Id
 * @property string $name 星座名称
 * @property int $start_month 开始月份
 * @property int $start_date 开始日期
 * @property int $end_month 结束月份
 * @property int $end_date 结束日期
 */
class ValentineConstellationsTable extends Table{
	protected $_name = 'valentine_constellations';
	
	/**
	 * @param string $class_name
	 * @return ValentineConstellationsTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('start_month', 'start_date', 'end_month', 'end_date'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('id'), 'int', array('min'=>0, 'max'=>255)),
			array(array('name'), 'string', array('max'=>10)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'name'=>'星座名称',
			'start_month'=>'开始月份',
			'start_date'=>'开始日期',
			'end_month'=>'结束月份',
			'end_date'=>'结束日期',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'name'=>'trim',
			'start_month'=>'intval',
			'start_date'=>'intval',
			'end_month'=>'intval',
			'end_date'=>'intval',
		);
	}
}