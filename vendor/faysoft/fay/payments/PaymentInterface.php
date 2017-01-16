<?php
namespace fay\payments;

interface PaymentInterface{
	/**
	 * 第三方支付同步跳转
	 * @return mixed
	 */
	public function callback();
	
	/**
	 * 第三方支付异步回调
	 * @return mixed
	 */
	public function notify();
	
	/**
	 * 交易查询
	 * @return mixed
	 */
	public function query();
	
	/**
	 * 退款
	 * @return mixed
	 */
	public function refund();
}