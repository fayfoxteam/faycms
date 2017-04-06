<?php
namespace faypay\services\methods\weixin;

use faypay\services\trade\TradePaymentService;

require_once __DIR__ . '/sdk/lib/WxPay.Api.php';
require_once __DIR__ . '/sdk/lib/WxPay.Notify.php';

class WeixinNotify extends \WxPayNotify{
    public function NotifyProcess($data, &$msg)
    {
        //根据外部订单号获取对应交易记录
        $trade_payment = TradePaymentService::service()->getByOutTradeNo($data['out_trade_no']);
    
        $trade_payment->onPaid($data['transaction_id'], $data['openid'], $data['total_fee'], strtotime($data['time_end']));
        
        return true;
    }
}