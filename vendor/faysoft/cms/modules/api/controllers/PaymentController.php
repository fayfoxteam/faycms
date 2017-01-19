<?php
namespace guangong\modules\api\controllers;

use fay\services\PaymentService;
use fay\services\TradeService;
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
	
	/**
	 * @parameter int $trade_id
	 * @parameter int $payment_id
	 */
	public function pay(){
		//表单验证
		$this->form()->setRules(array(
			array(array('trade_id', 'payment_id'), 'required'),
			array(array('trade_id', 'payment_id'), 'int', array('min'=>1)),
			array(array('trade_id'), 'exist', array(
				'table'=>'trades',
				'field'=>'id',
			)),
			array(array('payment_id'), 'exist', array(
				'table'=>'trades',
				'field'=>'id',
			)),
		))->setFilters(array(
			'id'=>'intval',
		))->setLabels(array(
			'id'=>'地区ID',
		))->check();
		
		$payment = PaymentService::service()->get($this->form()->getData('payment_id'));
		$trade = TradeService::service()->get($this->form()->getData('trade_id'));
		
		$paymentTrade = new PaymentTrade();
	}
}