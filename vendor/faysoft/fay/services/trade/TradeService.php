<?php
namespace fay\services\trade;

use fay\core\Service;

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
}