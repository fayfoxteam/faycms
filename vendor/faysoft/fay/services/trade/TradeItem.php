<?php
namespace fay\services\trade;

use fay\models\tables\TradesTable;
use fay\services\trade\state\ClosedTrade;
use fay\services\trade\state\CreateTrade;
use fay\services\trade\state\PaidTrade;
use fay\services\trade\state\StateInterface;

class TradeItem{
	/**
	 * @var StateInterface
	 */
	private $state;
	
	/**
	 * @var array 交易信息
	 */
	private $trade;
	
	public function __construct($trade_id){
		$trade = TradesTable::model()->find($trade_id);
		if(!$trade){
			throw new TradeErrorException('指定交易ID不存在');
		}
		$this->trade = $trade;
		
		switch($trade['status']){
			case TradesTable::STATUS_WAIT_PAY:
				$this->state = new CreateTrade();
				break;
			case TradesTable::STATUS_PAID:
				$this->state = new PaidTrade();
				break;
			case TradesTable::STATUS_CLOSED:
				$this->state = new ClosedTrade();
				break;
			default:
				throw new TradeErrorException('交易状态异常');
		}
	}
	
	/**
	 * 设置状态
	 * @param StateInterface $state
	 */
	public function setState(StateInterface $state){
		$this->state = $state;
	}
	
	/**
	 * 获取交易详情
	 * @return array
	 */
	public function getTrade(){
		return $this->trade;
	}
	
	/**
	 * 执行支付
	 * @param int $payment_id 支付方式ID
	 */
	public function pay($payment_id){
		$this->state->pay($this, $payment_id);
	}
	
	/**
	 * 魔术方法获取交易信息
	 * @param string $name
	 * @return string
	 * @throws TradeErrorException
	 */
	public function __get($name){
		if(isset($this->trade[$name])){
			return $this->trade[$name];
		}else{
			throw new TradeErrorException("交易信息{$name}字段不存在");
		}
	}
}