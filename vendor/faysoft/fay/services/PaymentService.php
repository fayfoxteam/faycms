<?php
namespace fay\services;

use fay\core\Service;
use fay\models\tables\PaymentsTable;

class PaymentService extends Service{
	
	/**
	 * @param string $class_name
	 * @return PaymentService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 获取支付方式
	 * @param int $id 支付方式ID
	 * @return array|bool
	 */
	public function get($id){
		$payment = PaymentsTable::model()->find($id);
		if(!$payment){
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