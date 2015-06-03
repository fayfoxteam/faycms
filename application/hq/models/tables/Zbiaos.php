<?php
namespace hq\models\tables;

use fay\core\db\Table;

class Zbiaos extends Table{
    
    const TYPE_ELECTRICITY = 1;
    const TYPE_WATER = 2;
    
	protected $_name = 'zbiaos';
	
	/**
	 * @return Zbiaos
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'biao_id', 'zongzhi', 'created', 'updated'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('type'), 'int', array('min'=>-128, 'max'=>127)),
			array(array('biao_name'), 'string', array('max'=>128)),
			array(array('address', 'shuoming'), 'string', array('max'=>512)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'biao_id'=>'Biao Id',
			'type'=>'Type',
			'biao_name'=>'Biao Name',
			'zongzhi'=>'Zongzhi',
			'address'=>'Address',
			'shuoming'=>'Shuoming',
			'created'=>'Created',
			'updated'=>'Updated',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'biao_id'=>'intval',
			'type'=>'intval',
			'biao_name'=>'trim',
			'zongzhi'=>'intval',
			'address'=>'trim',
			'shuoming'=>'trim',
			'created'=>'intval',
			'updated'=>'intval',
		);
	}
}