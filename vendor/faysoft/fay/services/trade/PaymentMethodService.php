<?php
namespace fay\services\trade;

use fay\core\Service;
use fay\models\tables\PaymentsTable;

/**
 * 支付方式
 */
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
}