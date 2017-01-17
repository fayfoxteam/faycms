<?php
namespace guangong\modules\api\controllers;

use guangong\library\FrontController;
use fay\helpers\UrlHelper;
use fay\payments\PaymentConfig;
use fay\payments\PaymentTrade;
use fay\payments\weixin\WeixinPayment;

class PaymentController extends FrontController{
	public function wxjsapi(){
		$trade = new PaymentTrade('fayfox'.date("YmdHis"), 1);
		$trade->setNotifyUrl(UrlHelper::createUrl('payment/wx-notify'))
			->setBody('fayfox测试订单');
		
		$config = new PaymentConfig('1397762502');
		$config->setAppId('wxad76a044d8fad0ed');
		
		$payment = new WeixinPayment();
		$payment->jsApi($trade, $config);
	}
}