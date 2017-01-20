<?php
namespace fay\services\trade\payment_state;
use fay\services\trade\TradeException;
use fay\services\trade\TradePaymentItem;

/**
 * 交易支付记录待支付状态
 */
class CreateTradePayment implements PaymentStateInterface{
	
	/**
	 * 交易支付记录支付成功
	 * @param TradePaymentItem $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function onPaid(TradePaymentItem $trade_payment){
		//@todo 正常支付
	}
	
	/**
	 * 交易支付记录执行退款
	 * @param TradePaymentItem $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function refund(TradePaymentItem $trade_payment){
		throw new TradeException('未支付交易支付记录不能退款');
	}
	
	/**
	 * 交易支付记录关闭
	 * @param TradePaymentItem $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function close(TradePaymentItem $trade_payment){
		//@todo 正常关闭
	}
}