<?php
namespace fay\payments\weixin;

use fay\payments\PaymentInterface;

class WeixinPayment implements PaymentInterface{
    
    /**
     * 构建支付数据
     * @return mixed
     */
    public function build()
    {
        // TODO: Implement build() method.
    }
    
    /**
     * 第三方支付同步跳转
     * @return mixed
     */
    public function callback()
    {
        // TODO: Implement callback() method.
    }
    
    /**
     * 第三方支付异步回调
     * @return mixed
     */
    public function notify()
    {
        // TODO: Implement notify() method.
    }
    
    /**
     * 交易查询
     * @return mixed
     */
    public function query()
    {
        // TODO: Implement query() method.
    }
    
    /**
     * 退款
     * @return mixed
     */
    public function refund()
    {
        // TODO: Implement refund() method.
    }
}