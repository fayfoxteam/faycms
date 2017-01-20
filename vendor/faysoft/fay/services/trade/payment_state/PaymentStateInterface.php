<?php
namespace fay\services\trade\payment_state;
use fay\services\TradePaymentService;

/**
 * 交易支付记录状态接口
 */
interface PaymentStateInterface{
	/**
	 * 交易支付记录支付成功
	 * @param TradePaymentService $trade_payment
	 * @return bool
	 */
	public function onPaid(TradePaymentService $trade_payment);
	
	/**
	 * 交易支付记录执行退款
	 * @param TradePaymentService $trade_payment
	 * @return bool
	 */
	public function refund(TradePaymentService $trade_payment);
	
	/**
	 * 交易支付记录关闭
	 * @param TradePaymentService $trade_payment
	 * @return bool
	 */
	public function close(TradePaymentService $trade_payment);
}