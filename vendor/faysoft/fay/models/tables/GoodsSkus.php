<?php
namespace fay\models\tables;

use fay\core\db\Table;

/**
 * Goods Skus model
 *
 * @property int $goods_id
 * @property string $sku_key
 * @property float $price
 * @property int $quantity
 * @property string $tsces
 */
class GoodsSkus extends Table{
	protected $_name = 'goods_skus';
	protected $_primary = array('goods_id', 'sku_key');
	
	/**
	 * @param string $class_name
	 * @return GoodsSkus
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	public function rules(){
		return array(
			array(array('goods_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('quantity'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('sku_key'), 'string', array('max'=>255)),
			array(array('tsces'), 'string', array('max'=>50)),
			array(array('price'), 'float', array('length'=>8, 'decimal'=>2)),
		);
	}

	public function labels(){
		return array(
			'goods_id'=>'商品ID',
			'sku_key'=>'Key',
			'price'=>'价格',
			'quantity'=>'库存',
			'tsces'=>'商家编码',
		);
	}

	public function filters(){
		return array(
			'goods_id'=>'intval',
			'sku_key'=>'trim',
			'price'=>'floatval',
			'quantity'=>'intval',
			'tsces'=>'trim',
		);
	}
}