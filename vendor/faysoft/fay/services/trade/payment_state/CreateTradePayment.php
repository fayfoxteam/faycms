<?php
namespace fay\services\trade\payment_state;
use fay\services\trade\TradeException;
use fay\services\trade\TradePaymentService;

/**
 * 交易支付记录待支付状态
 */
class CreateTradePayment implements PaymentStateInterface{
	
	/**
	 * 交易支付记录支付成功
	 * @param TradePaymentService $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function onPaid(TradePaymentService $trade_payment){
		//@todo 正常支付
	}
	
	/**
	 * 交易支付记录执行退款
	 * @param TradePaymentService $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function refund(TradePaymentService $trade_payment){
		throw new TradeException('未支付交易支付记录不能退款');
	}
	
	/**
	 * 交易支付记录关闭
	 * @param TradePaymentService $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function close(TradePaymentService $trade_payment){
		//@todo 正常关闭
	}
}