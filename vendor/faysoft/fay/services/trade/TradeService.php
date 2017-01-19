<?php
namespace fay\services;

use fay\models\tables\TradesTable;
use fay\services\trade\state\ClosedTrade;
use fay\services\trade\state\CreateTrade;
use fay\services\trade\state\PaidTrade;
use fay\services\trade\state\StateInterface;

class TradeService{
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
		}
	}
	
	public function setState(StateInterface $state){
		$this->state = $state;
	}
	
	/**
	 * 执行支付
	 * @param int $payment_id 支付方式ID
	 */
	public function pay($payment_id){
		$this->state->pay($this, $payment_id);
	}
}