<?php
namespace fay\services;

use fay\core\Service;
use fay\helpers\NumberHelper;
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
	 * 获取支付方式
	 * @param int $id 支付方式ID
	 * @return array|bool
	 */
	public function get($id){
		$trade = TradesTable::model()->find($id);
		if(!$trade){
			return false;
		}
		
		
		return $trade;
	}
	
	/**
	 * 构造外部订单号
	 * @param $trade
	 * @return string
	 */
	public function getOutTradeNo($trade){
		if(NumberHelper::isInt($trade)){
			$trade = $this->get($trade);
		}
		
		return date('YmdHis', $trade['create_time']) . NumberHelper::toLength($trade['id'], 7);
	}
}