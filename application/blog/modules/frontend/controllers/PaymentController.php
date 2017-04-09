<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;
use fay\helpers\UrlHelper;
use fay\services\payment\methods\PaymentMethodConfigModel;
use fay\services\payment\methods\PaymentTradeModel;
use fay\services\payment\methods\weixin\WeixinPayment;

class PaymentController extends FrontController{
    public function wxjsapi(){
        $trade = new PaymentTradeModel('fayfox'.date("YmdHis"), 1);
        $trade->setNotifyUrl(UrlHelper::createUrl('payment/wx-notify'))
            ->setBody('fayfox测试订单');
        ;
        
        $config = new PaymentMethodConfigModel('123');
        $config->setAppId('wxad76a044d8fad0ed');
        
        $payment = new WeixinPayment();
        $payment->jsapi($trade, $config);
    }
}