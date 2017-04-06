<?php
namespace faypay\services\trade\state;

use faypay\services\trade\TradeItem;

/**
 * 交易状态接口
 */
interface StateInterface{
	/**
	 * 执行支付
	 * @param TradeItem $trade
	 * @param int $payment_method_id 支付方式ID
	 * @return bool
	 */
	public function pay(TradeItem $trade, $payment_method_id);
	
	/**
	 * 交易执行退款
	 * @param TradeItem $trade
	 * @return bool
	 */
	public function refund(TradeItem $trade);
	
	/**
	 * 关闭交易
	 * @param TradeItem $trade
	 * @return bool
	 */
	public function close(TradeItem $trade);
}