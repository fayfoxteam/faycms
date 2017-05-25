<?php
namespace fayshop\models\tables;

use fay\core\db\Table;

/**
 * Shop goods skus table model
 * 
 * @property int $goods_id 商品ID
 * @property string $sku_key SKU Key
 * @property float $price 价格
 * @property int $quantity 库存
 * @property string $tsces 商家编码
 */
class ShopGoodsSkusTable extends Table{
    protected $_name = 'shop_goods_skus';
    protected $_primary = array('goods_id', 'sku_key');
    
    /**
     * @param string $class_name
     * @return ShopGoodsSkusTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('goods_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('quantity'), 'int', array('min'=>0, 'max'=>16777215)),
            array(array('sku_key'), 'string', array('max'=>100)),
            array(array('tsces'), 'string', array('max'=>50)),
            array(array('price'), 'float', array('length'=>8, 'decimal'=>2)),
        );
    }

    public function labels(){
        return array(
            'goods_id'=>'商品ID',
            'sku_key'=>'SKU Key',
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