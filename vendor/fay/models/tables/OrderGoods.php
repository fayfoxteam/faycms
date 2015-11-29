<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Order Goods model
 * 
 * @property int $id
 * @property int $order_id
 * @property int $goods_id
 * @property string $title
 * @property float $price
 * @property int $num
 * @property string $sku_key
 * @property string $sku_properties_name
 */
class OrderGoods extends Table{
	protected $_name = 'order_goods';
	
	/**
	 * @return OrderGoods
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('goods_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('id', 'order_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('num'), 'int', array('min'=>0, 'max'=>65535)),
			array(array('title', 'sku_key'), 'string', array('max'=>255)),
			array(array('sku_properties_name'), 'string', array('max'=>500)),
			array(array('price'), 'float', array('length'=>8, 'decimal'=>2)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'order_id'=>'订单ID',
			'goods_id'=>'商品ID',
			'title'=>'商品标题',
			'price'=>'商品价格',
			'num'=>'购买数量',
			'sku_key'=>'SKU Key',
			'sku_properties_name'=>'SKU的值',
		);
	}

	public function filters(){
		return array(
			'id'=>'intval',
			'order_id'=>'intval',
			'goods_id'=>'intval',
			'title'=>'trim',
			'price'=>'floatval',
			'num'=>'intval',
			'sku_key'=>'trim',
			'sku_properties_name'=>'trim',
		);
	}
}