<?php
namespace faypay\services\trade\payment_state;

use faypay\services\trade\TradeException;
use faypay\services\trade\TradePaymentItem;

/**
 * 交易支付记录已付款状态
 */
class PaidTradePayment implements PaymentStateInterface{
    /**
     * 发起支付
     * @param TradePaymentItem $trade_payment
     * @throws TradeException
     * @return bool
     */
    public function pay(TradePaymentItem $trade_payment){
        throw new TradeException('已支付交易记录不能发起支付');
    }
    
    /**
     * 接收支付记录回调
     * @param TradePaymentItem $trade_payment
     * @param string $trade_no 第三方交易号
     * @param string $payer_account 第三方付款帐号
     * @param int $paid_fee 第三方回调时传过来的实付金额（单位：分）
     * @param int $pay_time 支付时间时间戳
     * @return bool
     * @throws TradeException
     */
    public function onPaid(TradePaymentItem $trade_payment, $trade_no, $payer_account, $paid_fee, $pay_time = 0){
        //第三方支付不可避免的会出现重复回调，抛出一个无所谓的异常就好了
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