<?php
namespace fay\models\tables;

use fay\core\db\Table;

class GoodsSkus extends Table{
	protected $_name = 'goods_skus';
	
	/**
	 * @return GoodsSkus
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'goods_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('quantity'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('prop_value_ids'), 'string', array('max'=>255)),
			array(array('tsces'), 'string', array('max'=>50)),
			array(array('price'), 'float', array('length'=>8, 'decimal'=>2)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'goods_id'=>'Goods Id',
			'prop_value_ids'=>'Prop Value Ids',
			'price'=>'Price',
			'quantity'=>'Quantity',
			'tsces'=>'商家编码',
		);
	}

	public function filters(){
		return array(
			'goods_id'=>'intval',
			'prop_value_ids'=>'trim',
			'price'=>'floatval',
			'quantity'=>'intval',
			'tsces'=>'trim',
		);
	}
}