<?php
namespace fay\services\trade\payment_state;
use fay\services\trade\TradeException;
use fay\services\TradePaymentService;

/**
 * 交易支付记录关闭后发生付款，这是一种异常状态。
 * 这类支付管理员可以后台操作原路退回。
 * 因为是第三方支付，重复支付理论上是不可避免的。
 * 例如：用户先用支付宝付款，但出于网络原因，支付宝付款成功的通知还没收到。
 * 此时用户又用微信支付付款，这样就会造成重复支付。
 * 实际应用中一般不会有用户傻到一直付款，但还是存在这种可能性。
 */
class PaidAfterClosedTradePayment implements PaymentStateInterface{
	
	/**
	 * 交易支付记录支付成功
	 * @param TradePaymentService $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function onPaid(TradePaymentService $trade_payment){
		throw new TradeException('已支付交易记录不能支付');
	}
	
	/**
	 * 交易支付记录执行退款
	 * @param TradePaymentService $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function refund(TradePaymentService $trade_payment){
		//@todo 执行退款
	}
	
	/**
	 * 交易支付记录关闭
	 * @param TradePaymentService $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function close(TradePaymentService $trade_payment){
		//@todo 退款后可以关闭
	}
}