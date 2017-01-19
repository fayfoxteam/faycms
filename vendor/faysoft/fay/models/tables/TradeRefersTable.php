<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * 交易引用关系表
 * 
 * @property int $id Id
 * @property int $trade_id 交易ID
 * @property int $type 交易类型
 * @property int $refer_id 关联ID
 */
class TradeRefersTable extends Table{
	protected $_name = 'trade_refers';
	
	/**
	 * @param string $class_name
	 * @return TradeRefersTable
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('id', 'trade_id', 'refer_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('type'), 'int', array('min'=>0, 'max'=>255)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'trade_id'=>'交易ID',
			'type'=>'交易类型',
			'refer_id'=>'关联ID',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'trade_id'=>'intval',
			'type'=>'intval',
			'refer_id'=>'intval',
		);
	}
}