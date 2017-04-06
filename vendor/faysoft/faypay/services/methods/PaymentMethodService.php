<?php
namespace faypay\services\methods;

use fay\core\Service;
use fay\models\tables\PaymentsTable;

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
		$payment_method = PaymentsTable::model()->find($id);
		//支付方式不存在、未启用或已删除，都返回false
		if(!$payment_method || !$payment_method['enabled'] || $payment_method['delete_time']){
			return false;
		}
		
		if($payment_method['config']){
			$payment_method['config'] = \json_decode($payment_method['config'], true);
		}
		
		if(!$payment_method['config']){
			$payment_method['config'] = array();
		}
		
		return $payment_method;
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
		/**
		 * @var $payment_obj PaymentMethodInterface
		 */
		$payment_obj = new $class_name;
		$payment_obj->notify();
	}
}