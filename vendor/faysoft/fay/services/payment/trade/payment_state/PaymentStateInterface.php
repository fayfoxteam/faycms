<?php
namespace fay\services\payment\trade\payment_state;

use fay\services\payment\trade\TradePaymentItem;

/**
 * 交易支付记录状态接口
 */
interface PaymentStateInterface{
	/**
	 * 发起支付
	 * @param TradePaymentItem $trade_payment
	 * @return bool
	 */
	public function pay(TradePaymentItem $trade_payment);
	
	/**
	 * 接收支付记录回调
	 * @param TradePaymentItem $trade_payment
	 * @param string $trade_no 第三方交易号
	 * @param string $payer_account 第三方付款帐号
	 * @param int $paid_fee 第三方回调时传过来的实付金额（单位：分）
	 * @param int $pay_time 支付时间时间戳
	 * @return bool
	 */
	public function onPaid(TradePaymentItem $trade_payment, $trade_no, $payer_account, $paid_fee, $pay_time = 0);
	
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