<?php
namespace fay\services\trade\payment_state;
use fay\services\trade\TradePaymentItem;

/**
 * 交易支付记录状态接口
 */
interface PaymentStateInterface{
	/**
	 * 交易支付记录支付成功
	 * @param TradePaymentItem $trade_payment
	 * @return bool
	 */
	public function onPaid(TradePaymentItem $trade_payment);
	
	/**
	 * 交易支付记录执行退款
	 * @param TradePaymentItem $trade_payment
	 * @return bool
	 */
	public function refund(TradePaymentItem $trade_payment);
	
	/**
	 * 交易支付记录关闭
	 * @param TradePaymentItem $trade_payment
	 * @return bool
	 */
	public function close(TradePaymentItem $trade_payment);
}