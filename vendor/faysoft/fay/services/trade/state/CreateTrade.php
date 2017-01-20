<?php
namespace fay\services\trade\state;
use fay\helpers\RequestHelper;
use fay\models\tables\TradePaymentsTable;
use fay\payments\PaymentService;
use fay\services\trade\PaymentMethodService;
use fay\services\trade\TradeErrorException;
use fay\services\trade\TradeException;
use fay\services\trade\TradePaymentItem;
use fay\services\trade\TradeItem;

/**
 * 交易待支付状态
 */
class CreateTrade implements StateInterface{
	
	/**
	 * 执行支付
	 * @param TradeItem $trade
	 * @param int $payment_id 支付方式ID
	 * @return bool
	 * @throws TradeErrorException
	 */
	public function pay(TradeItem $trade, $payment_id){
		//获取支付方式
		$payment = PaymentMethodService::service()->get($payment_id);
		if(!$payment){
			throw new TradeErrorException('指定支付方方式不存在');
		}
		
		//生成支付记录
		$trade_payment = $this->createTradePayment($trade, $payment['id']);
		
		PaymentService::service()->buildPay($trade_payment);
	}
	
	/**
	 * @param TradeItem $trade
	 * @param $payment_id
	 * @return TradePaymentItem
	 */
	private function createTradePayment(TradeItem $trade, $payment_id){
		$trade_payment_id =  TradePaymentsTable::model()->insert(array(
			'trade_id'=>$trade->id,
			'total_fee'=>$trade->total_fee,
			'payment_id'=>$payment_id,
			'create_time'=>\F::app()->current_time,
			'status'=>TradePaymentsTable::STATUS_WAIT_PAY,
			'create_ip'=>RequestHelper::ip2int(\F::app()->ip),
			'trade_no'=>'',
			'payer_account'=>'',
			'paid_time'=>0,
		));
		
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