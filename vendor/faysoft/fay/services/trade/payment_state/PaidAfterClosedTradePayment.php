<?php
namespace fay\services\trade\payment_state;
use fay\services\trade\TradeException;
use fay\services\trade\TradePaymentItem;

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
	 * 发起支付
	 * @param TradePaymentItem $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function pay(TradePaymentItem $trade_payment){
		throw new TradeException('已异常支付交易记录不能发起支付');
	}
	
	/**
	 * 接收支付记录回调
	 * @param TradePaymentItem $trade_payment
	 * @param string $trade_no 第三方交易号
	 * @param string $payer_account 第三方付款帐号
	 * @param int $paid_fee 第三方回调时传过来的实付金额（单位：分）
	 * @throws TradeException
	 * @return bool
	 */
	public function onPaid(TradePaymentItem $trade_payment, $trade_no, $payer_account, $paid_fee){
		throw new TradeException('已支付交易记录不能支付');
	}
	
	/**
	 * 交易支付记录执行退款
	 * @param TradePaymentItem $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function refund(TradePaymentItem $trade_payment){
		//@todo 执行退款
	}
	
	/**
	 * 交易支付记录关闭
	 * @param TradePaymentItem $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function close(TradePaymentItem $trade_payment){
		//@todo 退款后可以关闭
	}
}