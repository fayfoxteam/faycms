<?php
namespace fay\services\trade\state;
use fay\services\trade\TradeException;
use fay\services\TradeService;

/**
 * 交易关闭状态
 */
class ClosedTrade implements StateInterface{
	
	/**
	 * 交易支付成功
	 * @param TradeService $trade
	 * @param int $payment_id 支付方式ID
	 * @return bool
	 * @throws TradeException
	 */
	public function pay(TradeService $trade, $payment_id){
		/*
		 * 已关闭交易发生支付行为，交易表不做处理。
		 * 交易支付记录表会记录错误状态，方便后期原路退回。
		 */
		throw new TradeException('已关闭交易不能支付');
	}
	
	/**
	 * 交易执行退款
	 * @param TradeService $trade
	 * @throws TradeException
	 * @return bool
	 */
	public function refund(TradeService $trade){
		throw new TradeException('已关闭交易不能退款');
	}
	
	/**
	 * 关闭交易
	 * @param TradeService $trade
	 * @throws TradeException
	 * @return bool
	 */
	public function close(TradeService $trade){
		throw new TradeException('已关闭交易不能重复关闭');
	}
}