<?php
namespace faypay\services\trade\state;

use faypay\services\trade\TradeException;
use faypay\services\trade\TradeItem;

/**
 * 交易已付款状态
 */
class PaidTrade implements StateInterface{
	
	/**
	 * 执行支付
	 * @param TradeItem $trade
	 * @param int $payment_method_id 支付方式ID
	 * @throws TradeException
	 * @return bool
	 */
	public function pay(TradeItem $trade, $payment_method_id){
		throw new TradeException('已付款交易不能重复支付');
	}
	
	/**
	 * 交易执行退款
	 * @param TradeItem $trade
	 * @return bool
	 */
	public function refund(TradeItem $trade){
		//@todo 执行退款
	}
	
	/**
	 * 关闭交易
	 * @param TradeItem $trade
	 * @throws TradeException
	 * @return bool
	 */
	public function close(TradeItem $trade){
		throw new TradeException('已付款交易不能直接关闭');
	}
}