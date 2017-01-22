<?php
namespace fay\services\payment\methods;

use fay\core\Service;
use fay\models\tables\PaymentsTable;
use fay\services\payment\methods\models\PaymentMethodConfigModel;
use fay\services\payment\methods\models\PaymentTradeModel;

class PaymentMethodService extends Service{
	/**
	 * @param string $class_name
	 * @return PaymentMethodService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 获取支付方式
	 * @param int $id 支付方式ID
	 * @return array|false
	 */
	public function get($id){
		$payment = PaymentsTable::model()->find($id);
		//支付方式不存在、未启用或已删除，都返回false
		if(!$payment || !$payment['enabled'] || $payment['deleted']){
			return false;
		}
		
		if($payment['config']){
			$payment['config'] = \json_decode($payment['config'], true);
		}
		
		if(!$payment['config']){
			$payment['config'] = array();
		}
		
		return $payment;
	}
    
    /**
     * 构建支付请求
     * @param PaymentTradeModel $payment_trade
     * @param PaymentMethodConfigModel $payment_config
     */
	public function buildPay(PaymentTradeModel $payment_trade, PaymentMethodConfigModel $payment_config){
		$code = $payment_config->getCode();
		$payment_code = explode(':', $code);
		$class_name = 'fay\\services\\payment\\methods\\' . $payment_code[0] . '\\' . ucfirst($payment_code[0]) . 'Payment';
		$payment_obj = new $class_name;
		$payment_obj->{$payment_code[1]}($payment_trade, $payment_config);
	}
	
	/**
	 * 执行支付回调
	 * @param string $code 支付方式编码
	 */
	public function notify($code){
		$payment_code = explode(':', $code);
		$class_name = 'fay\\services\\payment\\methods\\' . $payment_code[0] . '\\' . ucfirst($payment_code[0]) . 'Payment';
		$payment_obj = new $class_name;
		$payment_obj->notify($code);
	}
}