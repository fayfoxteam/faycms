<?php
namespace cms\services\shop;

use fay\core\Loader;
use fay\core\Service;

class ShopOrderService extends Service{
    /**
     * 留言创建后事件
     */
    const EVENT_CREATED = 'after_order_created';
    
    /**
     * @return ShopOrderService
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 创建订单
     * @param int $goods 包含商品信息的数组，其中包含：
     *     goods_id: 必选
     *     num: 购买数量
     *     sku: SKU信息
     * @param int $address_id 地址ID
     * @param string $buyer_note 买家留言
     * @param array $params 附加参数
     * @param int|null $user_id 用户ID，默认为当前登录用户ID
     */
    public static function create($goods, $address_id, $buyer_note = '', $params = array(), $user_id = null){
        //检查商品信息是否完整，且相关的商品ID，sku存在
        
        //检查所有商品是否属于同一个卖家
        
        //不能购买自己的商品
        
        //检查地址ID是否存在且地址是否属于用户本人
        
        //检查库存
        
        //计算邮费
        
        //创建订单
        
        //更新库存
        
        //触发事件
        \F::event()->trigger(self::EVENT_CREATED);
    }
}