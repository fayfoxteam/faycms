<?php
namespace faypay\services\trade;

use fay\core\Loader;
use fay\core\Service;
use faypay\models\tables\TradePaymentsTable;

class TradePaymentService extends Service{
    /**
     * 交易付款成功事件
     */
    const EVENT_PAID = 'trade_paid';
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 创建一条交易支付记录
     * @param int $trade_id 交易ID
     * @param int $total_fee 支付金额（单位：分）
     * @param int $payment_method_id 支付方式ID
     * @return int
     */
    public function create($trade_id, $total_fee, $payment_method_id){
        return TradePaymentsTable::model()->insert(array(
            'trade_id'=>$trade_id,
            'total_fee'=>$total_fee,
            'payment_method_id'=>$payment_method_id,
            'create_time'=>\F::app()->current_time,
            'status'=>TradePaymentsTable::STATUS_WAIT_PAY,
            'create_ip'=>\F::app()->ip_int,
            'trade_no'=>'',
            'payer_account'=>'',
            'pay_time'=>0,
        ));
    }
    
    /**
     * 根据支付记录ID，获取一个支付记录实例
     * @param int $trade_payment_id
     * @return TradePaymentItem
     */
    public function get($trade_payment_id){
        return new TradePaymentItem($trade_payment_id);
    }
    
    /**
     * 根据外部订单号，获取支付记录实例
     * @param string $out_trade_no 外部订单号
     * @return TradePaymentItem
     * @throws TradeErrorException
     */
    public function getByOutTradeNo($out_trade_no){
        $trade_payment_id = substr($out_trade_no, -7);
        $trade_payment = $this->get($trade_payment_id);
        if(date('Ymd', $trade_payment->create_time) != substr($out_trade_no, 0, 8)){
            //系统暂时不支持1000万以上的自递增ID
            //虽然可以做处理，但是没什么必要，真的到了千万级，肯定要改策略的
            throw new TradeErrorException('支付记录自递增ID已经溢出');
        }
        
        return $trade_payment;
    }
}