<?php
namespace fay\services\trade\payment_state;
use fay\services\trade\TradeException;
use fay\services\trade\TradePaymentItem;

/**
 * 交易支付记录已付款状态
 */
class PaidTradePayment implements PaymentStateInterface{
	
	/**
	 * 交易支付记录支付成功
	 * @param TradePaymentItem $trade_payment
	 * @return bool
	 * @throws TradeException
	 */
	public function onPaid(TradePaymentItem $trade_payment){
		throw new TradeException('已付款交易支付记录不能重复支付');
	}
	
	/**
	 * 交易支付记录执行退款
	 * @param TradePaymentItem $trade_payment
	 * @return bool
	 */
	public function refund(TradePaymentItem $trade_payment){
		//@todo 正常退款
	}
	
	/**
	 * 交易支付记录关闭
	 * @param TradePaymentItem $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function close(TradePaymentItem $trade_payment){
		throw new TradeException('已付款交易支付记录不能直接关闭');
	}
}