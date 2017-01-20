<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;
use fay\helpers\UrlHelper;
use fay\payments\PaymentConfigModel;
use fay\payments\PaymentTradeModel;
use fay\payments\weixin\WeixinPayment;

class PaymentController extends FrontController{
	public function wxjsapi(){
		$trade = new PaymentTradeModel('fayfox'.date("YmdHis"), 1);
		$trade->setNotifyUrl(UrlHelper::createUrl('payment/wx-notify'))
			->setBody('fayfox测试订单');
		;
		
		$config = new PaymentConfigModel('123');
		$config->setAppId('wxad76a044d8fad0ed');
		
		$payment = new WeixinPayment();
		$payment->jsapi($trade, $config);
	}
}