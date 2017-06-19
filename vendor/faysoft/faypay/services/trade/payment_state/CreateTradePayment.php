<?php
namespace faypay\services\trade\payment_state;

use fay\core\Exception;
use fay\helpers\UrlHelper;
use faypay\models\PaymentMethodConfigModel;
use faypay\models\PaymentTradeModel;
use faypay\models\tables\TradePaymentsTable;
use faypay\models\tables\TradesTable;
use faypay\services\methods\PaymentMethodService;
use faypay\services\trade\TradeException;
use faypay\services\trade\TradePaymentItem;
use faypay\services\trade\TradePaymentService;

/**
 * 交易支付记录待支付状态
 */
class CreateTradePayment implements PaymentStateInterface{
    /**
     * 发起支付
     * @param TradePaymentItem $trade_payment
     * @return bool|void
     * @throws TradeException
     */
    public function pay(TradePaymentItem $trade_payment){
        //实例化用于支付的支付方式配置模型
        $payment_method = $trade_payment->getPaymentMethod();
        $payment_config = new PaymentMethodConfigModel($payment_method['code']);
        $payment_config->setMchId($payment_method['config']['mch_id'])
            ->setAppId($payment_method['config']['app_id'])
            ->setAppSecret($payment_method['config']['app_secret'])
            ->setKey($payment_method['config']['key'])
        ;
        
        //实例化用于支付的交易数据模型
        $trade = $trade_payment->getTrade();
        $payment_trade = new PaymentTradeModel();
        $payment_trade->setOutTradeNo($trade_payment->getOutTradeNo())
            ->setTotalFee($trade_payment->total_fee)
            ->setNotifyUrl(UrlHelper::createUrl('faypay/api/payment/notify:code='.$payment_method['code']))
            ->setBody($trade->body)
            ->setTradePaymentId($trade_payment->id)
            ->setReturnUrl($trade->return_url)
            ->setShowUrl($trade->show_url)
        ;
        
        //调用支付模块
        PaymentMethodService::service()->buildPay($payment_trade, $payment_config);
    }
    
    /**
     * 接收支付记录回调
     * @param TradePaymentItem $trade_payment
     * @param string $trade_no 第三方交易号
     * @param string $payer_account 第三方付款帐号
     * @param int $paid_fee 第三方回调时传过来的实付金额（传进来的时候要确保单位已经转换为“分”）
     * @param int $pay_time 支付时间时间戳
     * @return bool
     * @throws Exception
     */
    public function onPaid(TradePaymentItem $trade_payment, $trade_no, $payer_account, $paid_fee, $pay_time = 0){
        //能获取到准确支付时间的话，以支付时间为准，若不能获取到准确支付时间，默认为异步通知时间
        $pay_time || $pay_time = \F::app()->current_time;
        try{
            \F::db()->beginTransaction();
            
            //将当前支付记录标记为已支付
            $trade_payment->status = TradePaymentsTable::STATUS_PAID;
            $trade_payment->trade_no = $trade_no;
            $trade_payment->payer_account = $payer_account;
            $trade_payment->paid_fee = $paid_fee;
            $trade_payment->notify_time = \F::app()->current_time;
            $trade_payment->pay_time = $pay_time;
            $trade_payment->save();
            
            //将相同交易的其它待支付记录标记为已关闭
            TradePaymentsTable::model()->update(array(
                'status'=>TradePaymentsTable::STATUS_CLOSED,
            ), array(
                'trade_id = ' . $trade_payment->trade_id,
                'status = ' . TradePaymentsTable::STATUS_WAIT_PAY,
            ));
            
            //将对应交易记录标记为已支付
            $trade = $trade_payment->getTrade();
            $trade->paid_fee = $paid_fee;
            $trade->status = TradesTable::STATUS_PAID;
            $trade->pay_time = $pay_time;
            $trade->save();
            
            \F::db()->commit();
            
            $trade_payment->setState(new PaidTradePayment());
        }catch(Exception $e){
            \F::db()->rollBack();
            throw $e;
        }
        
        //触发支付成功事件
        \F::event()->trigger(TradePaymentService::EVENT_PAID, $trade_payment);
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