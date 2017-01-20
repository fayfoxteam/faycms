<?php
namespace fay\payments;

use fay\core\Service;
use fay\helpers\UrlHelper;
use fay\services\trade\TradePaymentItem;

class PaymentService extends Service{
    /**
     * @param string $class_name
     * @return PaymentService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 构建支付请求
     * @param TradePaymentItem $trade_payment
     */
    public function buildPay(TradePaymentItem $trade_payment){
        //实例化用于支付的交易数据模型
        $trade = $trade_payment->getTrade();
        $payment_trade = new PaymentTradeModel();
        $payment_trade->setOutTradeNo($trade_payment->getOutTradeNo())
            ->setTotalFee($trade_payment->total_fee)
            ->setNotifyUrl(UrlHelper::createUrl('api/payment/notify'))
            ->setBody($trade->body)
        ;
        
        //实例化用于支付的支付方式配置模型
        $payment = $trade_payment->getPayment();
        $payment_config = new PaymentConfigModel();
        $payment_config->setMchId($payment['config']['mch_id'])
            ->setAppId($payment['config']['app_id'])
            ->setAppSecret($payment['config']['app_secret'])
            ->setKey($payment['config']['key'])
        ;
    
        $payment_code = explode(':', $payment['code']);
        $class_name = 'fay\\payments\\' . $payment_code[0] . '\\' . ucfirst($payment_code[0]) . 'Payment';
        $payment_obj = new $class_name;
        $payment_obj->{$payment_code[1]}($payment_trade, $payment_config);
    }
}