<?php
namespace faypay\services\trade\payment_state;

use faypay\services\trade\TradeException;
use faypay\services\trade\TradePaymentItem;

/**
 * 交易支付记录关闭状态
 */
class ClosedTradePayment implements PaymentStateInterface{
	/**
	 * 发起支付
	 * @param TradePaymentItem $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function pay(TradePaymentItem $trade_payment){
		throw new TradeException('已关闭交易记录不能发起支付');
	}
	
	/**
	 * 接收支付记录回调
	 * @param TradePaymentItem $trade_payment
	 * @param string $trade_no 第三方交易号
	 * @param string $payer_account 第三方付款帐号
	 * @param int $paid_fee 第三方回调时传过来的实付金额（单位：分）
	 * @param int $pay_time 支付时间时间戳
	 * @return bool
	 */
	public function onPaid(TradePaymentItem $trade_payment, $trade_no, $payer_account, $paid_fee, $pay_time = 0){
		/*
		 * @todo 支付后变成PaidAfterClosed状态。
		 * 因为理论上第三方支付不可避免的会存在重复支付的情况，但是概率极低。
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