<?php
namespace fay\services\trade\state;
use fay\services\trade\TradeException;
use fay\services\trade\TradeService;

/**
 * 交易已付款状态
 */
class PaidTrade implements StateInterface{
	
	/**
	 * 执行支付
	 * @param TradeService $trade
	 * @param int $payment_id 支付方式ID
	 * @throws TradeException
	 * @return bool
	 */
	public function pay(TradeService $trade, $payment_id){
		throw new TradeException('已付款交易不能重复支付');
	}
	
	/**
	 * 交易执行退款
	 * @param TradeService $trade
	 * @return bool
	 */
	public function refund(TradeService $trade){
		//@todo 执行退款
	}
	
	/**
	 * 关闭交易
	 * @param TradeService $trade
	 * @throws TradeException
	 * @return bool
	 */
	public function close(TradeService $trade){
		throw new TradeException('已付款交易不能直接关闭');
	}
}