<?php
namespace fay\services;

use fay\models\tables\TradePaymentsTable;
use fay\models\tables\TradesTable;
use fay\services\trade\payment_state\ClosedTradePayment;
use fay\services\trade\payment_state\CreateTradePayment;
use fay\services\trade\payment_state\PaidTradePayment;
use fay\services\trade\payment_state\PaymentStateInterface;
use fay\services\trade\state\StateInterface;

class TradePaymentService{
	/**
	 * @var PaymentStateInterface
	 */
	private $state;
	
	/**
	 * @var array 交易信息
	 */
	private $trade_payment;
	
	public function __construct($trade_payment_id){
		$trade_payment = TradePaymentsTable::model()->find($trade_payment_id);
		$this->trade_payment = $trade_payment;
		
		switch($trade_payment['status']){
			case TradesTable::STATUS_WAIT_PAY:
				$this->state = new CreateTradePayment();
				break;
			case TradesTable::STATUS_PAID:
				$this->state = new PaidTradePayment();
				break;
			case TradesTable::STATUS_CLOSED:
				$this->state = new ClosedTradePayment();
				break;
		}
	}
	
	public function setState(PaymentStateInterface $state){
		$this->state = $state;
	}
	
	/**
	 * 接受支付回调
	 * @param int $payment_id 支付方式ID
	 */
	public function pay(){
		$this->state->pay($this);
	}
}