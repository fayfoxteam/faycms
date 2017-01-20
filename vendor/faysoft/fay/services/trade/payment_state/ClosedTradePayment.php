<?php
namespace fay\services\trade\payment_state;
use fay\services\trade\TradeException;
use fay\services\trade\TradePaymentItem;

/**
 * 交易支付记录关闭状态
 */
class ClosedTradePayment implements PaymentStateInterface{
	
	/**
	 * 交易支付记录支付成功
	 * @param TradePaymentItem $trade_payment
	 * @return bool
	 */
	public function onPaid(TradePaymentItem $trade_payment){
		/*
		 * @todo 支付后变成PaidAfterClosed状态。
		 * 因为理论上第三方支付不可避免的会存在重复支付的情况。
		 */
	}
	
	/**
	 * 交易支付记录执行退款
	 * @param TradePaymentItem $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function refund(TradePaymentItem $trade_payment){
		throw new TradeException('已关闭交易支付记录不能退款');
	}
	
	/**
	 * 交易支付记录关闭
	 * @param TradePaymentItem $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function close(TradePaymentItem $trade_payment){
		throw new TradeException('已关闭交易支付记录不能重复关闭');
	}
}