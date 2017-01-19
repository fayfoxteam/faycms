<?php
namespace fay\services\trade\payment_state;
use fay\services\trade\TradeException;
use fay\services\TradePaymentService;

/**
 * 交易支付记录关闭状态
 */
class ClosedTradePayment implements PaymentStateInterface{
	
	/**
	 * 交易支付记录支付成功
	 * @param TradePaymentService $trade_payment
	 * @return bool
	 */
	public function pay(TradePaymentService $trade_payment){
		/*
		 * @todo 支付后变成PaidAfterClosed状态。
		 * 因为理论上第三方支付不可避免的会存在重复支付的情况。
		 */
	}
	
	/**
	 * 交易支付记录执行退款
	 * @param TradePaymentService $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function refund(TradePaymentService $trade_payment){
		throw new TradeException('已关闭交易支付记录不能退款');
	}
	
	/**
	 * 交易支付记录关闭
	 * @param TradePaymentService $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function close(TradePaymentService $trade_payment){
		throw new TradeException('已关闭交易支付记录不能重复关闭');
	}
}