<?php
namespace fay\models\tables;

use fay\core\db\Table;

class VouchersTable extends Table{
	/**
	 * 类型-现金卷
	 */
	const TYPE_CASH = 1;

	/**
	 * 类型-折扣卷
	 */
	const TYPE_DISCOUNT = 2;
	
	protected $_name = 'vouchers';

	/**
	 * @param string $class_name
	 * @return VouchersTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('user_id', 'create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('id', 'cat_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('counts'), 'int', array('min'=>-32768, 'max'=>32767)),
			array(array('type'), 'int', array('min'=>0, 'max'=>255)),
			array(array('sn'), 'string', array('max'=>30)),
			array(array('amount'), 'float', array('length'=>6, 'decimal'=>2)),
			array(array('delete_time'), 'range', array('range'=>array(0, 1))),
			array(array('start_time', 'end_time'), 'datetime'),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'cat_id'=>'分类',
			'sn'=>'Sn',
			'amount'=>'金额/折扣',
			'user_id'=>'User Id',
			'start_time'=>'开始时间',
			'end_time'=>'结束时间',
			'type'=>'类型',
			'delete_time'=>'删除时间',
			'create_time'=>'创建时间',
			'counts'=>'剩余次数',
		);
	}

	public function filters(){
		return array(
			'cat_id'=>'intval',
			'sn'=>'trim',
			'amount'=>'floatval',
			'user_id'=>'intval',
			'start_time'=>'trim',
			'end_time'=>'trim',
			'type'=>'intval',
			'delete_time'=>'intval',
			'counts'=>'intval',
		);
	}
}