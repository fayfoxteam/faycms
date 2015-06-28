<?php
namespace fay\models\tables;

use fay\core\db\Table;

class GoodsPropValues extends Table{
	protected $_name = 'goods_prop_values';
	
	/**
	 * @return GoodsPropValues
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'prop_value_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('goods_id', 'prop_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('prop_value_alias'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'goods_id'=>'Goods Id',
			'prop_id'=>'Prop Id',
			'prop_value_id'=>'Prop Value Id',
			'prop_value_alias'=>'Prop Value Alias',
		);
	}

	public function filters(){
		return array(
			'goods_id'=>'intval',
			'prop_id'=>'intval',
			'prop_value_id'=>'intval',
			'prop_value_alias'=>'trim',
		);
	}
}