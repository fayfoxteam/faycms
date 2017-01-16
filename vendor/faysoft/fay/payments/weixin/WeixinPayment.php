<?php
namespace fay\payments\weixin;

use fay\payments\PaymentConfig;
use fay\payments\PaymentInterface;
use fay\payments\PaymentTrade;

class WeixinPayment implements PaymentInterface{
	/**
	 * jsapi支付方式
	 * @param PaymentTrade $trade
	 * @param PaymentConfig $config
	 * @throws \WxPayException
	 */
	public function jsApi(PaymentTrade $trade, PaymentConfig $config){
		require_once __DIR__ . '/sdk/example/WxPay.JsApiPay.php';
        
		//①、获取用户openid
		$tools = new \JsApiPay();
		$openId = $tools->GetOpenid();

		//②、统一下单
		$input = new \WxPayUnifiedOrder();
		$input->SetBody("test");
		$input->SetAttach("test");
		$input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
		$input->SetTotal_fee("1");
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = \WxPayApi::unifiedOrder($input);
		dump($order);
		$jsApiParameters = $tools->GetJsApiParameters($order);
		
		require __DIR__ . '/views/jsapi.php';
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