<?php
namespace fay\services\trade\payment_state;
use fay\helpers\UrlHelper;
use fay\payments\PaymentConfigModel;
use fay\payments\PaymentService;
use fay\payments\PaymentTradeModel;
use fay\services\trade\TradeException;
use fay\services\trade\TradePaymentItem;

/**
 * 交易支付记录待支付状态
 */
class CreateTradePayment implements PaymentStateInterface{
	/**
	 * 发起支付
	 * @param TradePaymentItem $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function pay(TradePaymentItem $trade_payment){
		//实例化用于支付的交易数据模型
		$trade = $trade_payment->getTrade();
		$payment_trade = new PaymentTradeModel();
		$payment_trade->setOutTradeNo($trade_payment->getOutTradeNo())
			->setTotalFee($trade_payment->total_fee)
			->setNotifyUrl(UrlHelper::createUrl('api/payment/notify'))
			->setBody($trade->body)
			->setTradePaymentId($trade_payment->id)
		;
		
		//实例化用于支付的支付方式配置模型
		$payment = $trade_payment->getPayment();
		$payment_config = new PaymentConfigModel();
		$payment_config->setMchId($payment['config']['mch_id'])
			->setAppId($payment['config']['app_id'])
			->setAppSecret($payment['config']['app_secret'])
			->setKey($payment['config']['key'])
		;
		
		//调用支付模块
		PaymentService::service()->buildPay($payment['code'], $payment_trade, $payment_config);
	}
	
	/**
	 * 接收支付记录回调
	 * @param TradePaymentItem $trade_payment
	 * @throws TradeException
	 * @return bool
	 */
	public function onPaid(TradePaymentItem $trade_payment){
		//@todo 正常支付
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