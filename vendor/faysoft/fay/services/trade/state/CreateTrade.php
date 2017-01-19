<?php
namespace fay\services\trade\state;
use fay\services\trade\TradeException;
use fay\services\TradeService;

/**
 * 交易待支付状态
 */
class CreateTrade implements StateInterface{
	
	/**
	 * 执行支付
	 * @param TradeService $trade
	 * @param int $payment_id 支付方式ID
	 * @return bool
	 */
	public function pay(TradeService $trade, $payment_id){
		//@todo 执行支付
	}
	
	/**
	 * 交易执行退款
	 * @param TradeService $trade
	 * @return bool
	 * @throws TradeException
	 */
	public function refund(TradeService $trade){
		throw new TradeException('未支付交易不能退款');
	}
	
	/**
	 * 关闭交易
	 * @param TradeService $trade
	 * @return bool
	 */
	public function close(TradeService $trade){
		//@todo 正常关闭
	}
}