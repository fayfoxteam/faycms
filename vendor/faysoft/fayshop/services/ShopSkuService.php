<?php
namespace cms\services\shop;

use fay\core\Loader;
use fay\core\Service;
use fay\helpers\ArrayHelper;
use fayshop\models\tables\ShopGoodsCatPropsTable;
use fayshop\models\tables\ShopGoodsPropValuesTable;

class ShopSkuService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 根据商品ID和Sku Key获取属性名和属性值
     * @param int $goods_id 商品ID
     * @param string $sku_key
     * @return string
     */
    public static function getPropertiesNameByKey($goods_id, $sku_key){
        $sku_items = explode(';', $sku_key);
        $sku_map = array();
        foreach($sku_items as $p){
            list($prop_id, $value_id) = explode(':', $p);
            $sku_map[$prop_id] = $value_id;
        }
        
        $props_ids = array_keys($sku_map);
        $props_value_ids = array_values($sku_map);
        
        $props = ShopGoodsCatPropsTable::model()->fetchAll(array(
            'id IN (?)'=>$props_ids,
        ), 'id,title');
        $prop_map = ArrayHelper::column($props, 'title', 'id');
        
        $values = ShopGoodsPropValuesTable::model()->fetchAll(array(
            'goods_id = ?'=>$goods_id,
            'prop_id IN (?)'=>$props_ids,
            'prop_value_id IN (?)'=>$props_value_ids,
        ), 'prop_value_id,prop_value_alias');
        $value_map = ArrayHelper::column($values, 'prop_value_alias', 'prop_value_id');
        
        $prop_name = array();
        foreach($sku_map as $k => $v){
            $prop_name[] = "{$prop_map[$k]}:{$value_map[$v]}";
        }
        
        return implode(';', $prop_name);
    }
}