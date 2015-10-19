<?php
namespace fay\models\shop;

use fay\core\Model;

class Sku extends Model{
	/**
	 * @return Sku
	 */
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 根据Sku Key获取属性名和属性值
	 * @param int $goods_id 商品ID
	 * @param string $sku_key
	 */
	public function getPropertiesNameByKey($goods_id, $sku_key){
		$props = explode(';', $sku_key);
		$prop_map = array();
		foreach($props as $p){
			$prop_exploded = explode(':', $p);
			$prop_id = $prop_exploded[0];
			$value_id = $prop_exploded[1];
			$prop_map[$prop_id] = $value_id;
		}
		
	}
}