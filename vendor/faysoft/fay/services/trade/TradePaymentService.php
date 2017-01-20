<?php
namespace fay\services\trade;

use fay\core\Service;
use fay\helpers\RequestHelper;
use fay\models\tables\TradePaymentsTable;

class TradePaymentService extends Service{
	/**
	 * @param string $class_name
	 * @return TradePaymentService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 创建一笔交易记录
	 * @param int $trade_id 交易ID
	 * @param int $total_fee 支付金额（单位：分）
	 * @param int $payment_id 支付方式ID
	 * @return int
	 */
	public function create($trade_id, $total_fee, $payment_id){
		return TradePaymentsTable::model()->insert(array(
			'trade_id'=>$trade_id,
			'total_fee'=>$total_fee,
			'payment_id'=>$payment_id,
			'create_time'=>\F::app()->current_time,
			'status'=>TradePaymentsTable::STATUS_WAIT_PAY,
			'create_ip'=>RequestHelper::ip2int(\F::app()->ip),
			'trade_no'=>'',
			'payer_account'=>'',
			'paid_time'=>0,
		));
	}
	
	/**
	 * 根据支付记录ID，获取一个支付记录实例
	 * @param int $trade_payment_id
	 * @return TradePaymentItem
	 */
	public function getItem($trade_payment_id){
		return new TradePaymentItem($trade_payment_id);
	}
}