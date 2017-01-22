<?php
namespace fay\payments;

use fay\core\Service;
use fay\helpers\UrlHelper;
use fay\services\trade\TradePaymentItem;

class PaymentService extends Service{
	/**
	 * @param string $class_name
	 * @return PaymentService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
    
    /**
     * 构建支付请求
     * @param PaymentTradeModel $payment_trade
     * @param PaymentConfigModel $payment_config
     */
	public function buildPay(PaymentTradeModel $payment_trade, PaymentConfigModel $payment_config){
		$code = $payment_config->getCode();
		$payment_code = explode(':', $code);
		$class_name = 'fay\\payments\\' . $payment_code[0] . '\\' . ucfirst($payment_code[0]) . 'Payment';
		$payment_obj = new $class_name;
		$payment_obj->{$payment_code[1]}($payment_trade, $payment_config);
	}
	
	/**
	 * 执行支付回调
	 * @param string $code 支付方式编码
	 */
	public function notify($code){
		$payment_code = explode(':', $code);
		$class_name = 'fay\\payments\\' . $payment_code[0] . '\\' . ucfirst($payment_code[0]) . 'Payment';
		$payment_obj = new $class_name;
		$payment_obj->notify($code);
	}
}