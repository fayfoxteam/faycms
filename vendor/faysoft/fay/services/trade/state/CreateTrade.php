<?php
namespace fay\services\trade\state;
use fay\helpers\RequestHelper;
use fay\helpers\UrlHelper;
use fay\models\tables\TradePaymentsTable;
use fay\payments\PaymentConfigModel;
use fay\payments\PaymentService;
use fay\payments\PaymentTradeModel;
use fay\payments\weixin\WeixinPayment;
use fay\services\PaymentMethodService;
use fay\services\trade\TradeErrorException;
use fay\services\trade\TradeException;
use fay\services\TradePaymentService;
use fay\services\TradeService;

/**
 * 交易待支付状态
 */
class CreateTrade implements StateInterface{
	
	/**
	 * 执行支付
	 * @param TradeService $trade
	 * @param int $payment_id 支付方式ID
	 * @return bool
	 * @throws TradeErrorException
	 */
	public function pay(TradeService $trade, $payment_id){
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
	 * @param TradeService $trade
	 * @param $payment_id
	 * @return TradePaymentService
	 */
	private function createTradePayment(TradeService $trade, $payment_id){
		$trade_payment_id =  TradePaymentsTable::model()->insert(array(
			'trade_id'=>$trade->id,
			'create_time'=>\F::app()->current_time,
			'paid_time'=>0,
			'status'=>TradePaymentsTable::STATUS_WAIT_PAY,
			'create_ip'=>RequestHelper::ip2int(\F::app()->ip),
			'payment_id'=>$payment_id,
			'trade_no'=>'',
			'payer_account'=>'',
		));
		
		return new TradePaymentService($trade_payment_id);
	}
	
	/**
	 * 交易执行退款
	 * @param TradeService $trade
	 * @return bool
	 * @throws TradeException
	 */
	public function refund(TradeService $trade){
		throw new TradeException('未支付交易不能退款');
	}
	
	/**
	 * 关闭交易
	 * @param TradeService $trade
	 * @return bool
	 */
	public function close(TradeService $trade){
		//@todo 正常关闭
	}
}