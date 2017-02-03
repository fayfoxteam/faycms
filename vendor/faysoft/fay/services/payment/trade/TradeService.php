<?php
namespace fay\services\payment\trade;

use fay\core\Service;
use fay\helpers\RequestHelper;
use fay\models\tables\TradesTable;

class TradeService extends Service{
	/**
	 * @param string $class_name
	 * @return TradeService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 根据交易ID，获取一个交易实例
	 * @param int $trade_id
	 * @return TradeItem
	 */
	public function get($trade_id){
		return new TradeItem($trade_id);
	}
	
	/**
	 * 创建一笔交易
	 * @param int $total_fee 支付金额（单位：分）
	 * @param string $subject 订单描述
	 * @param null|string $body 订单描述
	 * @param null|int $user_id 用户ID（默认为当前登录用户ID）
	 * @param int $expire_time 过期时间戳（为0则不过期）
	 * @return int 交易ID
	 */
	public function create($total_fee, $subject, $body = null, $user_id = null, $expire_time = 0){
		$body === null && $body = $subject;
		$user_id === null && $user_id = \F::app()->current_user;
		
		$trade_id = TradesTable::model()->insert(array(
			//用户输入
			'total_fee'=>$total_fee,
			'subject'=>$subject,
			'body'=>$body,
			'user_id'=>$user_id,
			'expire_time'=>$expire_time,
			//系统自动填写
			'create_time'=>\F::app()->current_time,
			'create_ip'=>RequestHelper::ip2int(\F::app()->ip),
			//默认值
			'paid_fee'=>0,
			'trade_payment_id'=>0,
			'refund_fee'=>0,
			'status'=>TradesTable::STATUS_WAIT_PAY,
			'pay_time'=>0,
		));
		
		return $trade_id;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}