<?php
namespace faypay\services\trade\state;

use faypay\services\methods\PaymentMethodService;
use faypay\services\trade\TradeErrorException;
use faypay\services\trade\TradeException;
use faypay\services\trade\TradeItem;
use faypay\services\trade\TradePaymentItem;
use faypay\services\trade\TradePaymentService;

/**
 * 交易待支付状态
 */
class CreateTrade implements StateInterface{
    
    /**
     * 执行支付
     * @param TradeItem $trade
     * @param int $payment_method_id 支付方式ID
     * @return bool
     * @throws TradeErrorException
     */
    public function pay(TradeItem $trade, $payment_method_id){
        //获取支付方式
        $payment_method = PaymentMethodService::service()->get($payment_method_id);
        if(!$payment_method){
            throw new TradeErrorException('指定支付方方式不存在');
        }
        
        //生成支付记录
        $trade_payment = $this->createTradePayment($trade, $payment_method['id']);
        //将支付方式数组和交易详情传递给支付记录对象，免得再搜一次
        $trade_payment->setPaymentMethod($payment_method);
        $trade_payment->setTrade($trade);
        
        //调用支付
        $trade_payment->pay();
    }
    
    /**
     * 创建一个支付记录，并返回支付记录实例
     * @param TradeItem $trade
     * @param $payment_method_id
     * @return TradePaymentItem
     */
    private function createTradePayment(TradeItem $trade, $payment_method_id){
        $trade_payment_id = TradePaymentService::service()->create($trade->id, $trade->total_fee, $payment_method_id);
        
        return new TradePaymentItem($trade_payment_id);
    }
    
    /**
     * 交易执行退款
     * @param TradeItem $trade
     * @return bool
     * @throws TradeException
     */
    public function refund(TradeItem $trade){
        throw new TradeException('未支付交易不能退款');
    }
    
    /**
     * 关闭交易
     * @param TradeItem $trade
     * @return bool
     */
    public function close(TradeItem $trade){
        //@todo 正常关闭
    }
}