<?php
namespace fay\services\trade\state;
use fay\services\trade\TradeService;

/**
 * 交易状态接口
 */
interface StateInterface{
	/**
	 * 执行支付
	 * @param TradeService $trade
	 * @param int $payment_id 支付方式ID
	 * @return bool
	 */
	public function pay(TradeService $trade, $payment_id);
	
	/**
	 * 交易执行退款
	 * @param TradeService $trade
	 * @return bool
	 */
	public function refund(TradeService $trade);
	
	/**
	 * 关闭交易
	 * @param TradeService $trade
	 * @return bool
	 */
	public function close(TradeService $trade);
}