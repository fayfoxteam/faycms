<?php
namespace guangong\modules\api\controllers;

use guangong\library\FrontController;
use fay\helpers\UrlHelper;
use fay\payments\PaymentConfig;
use fay\payments\PaymentTrade;
use fay\payments\weixin\WeixinPayment;

class PaymentController extends FrontController{
	public function wxjsapi(){
		$trade = new PaymentTrade();
		$trade->setOutTradeNo('fayfox'.date("YmdHis"))
			->setTotalFee(1)
			->setNotifyUrl(UrlHelper::createUrl('payment/wx-notify'))
			->setBody('fayfox测试订单')
		;
		
		$config = new PaymentConfig();
		$config->setMchId('1397762502')
			->setAppId('wxad76a044d8fad0ed')
			->setAppSecret('88efdec5df431446c3c42a8ee4004b9d')
			->setKey('abcdefghijklmnopqrstuvwxyz123456')
		;
		
		$payment = new WeixinPayment();
		$payment->jsApi($trade, $config);
	}
}