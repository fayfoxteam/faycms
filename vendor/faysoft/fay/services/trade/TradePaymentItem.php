<?php
namespace fay\services\trade;

use fay\helpers\NumberHelper;
use fay\models\tables\PaymentsTable;
use fay\models\tables\TradePaymentsTable;
use fay\services\trade\payment_state\ClosedTradePayment;
use fay\services\trade\payment_state\CreateTradePayment;
use fay\services\trade\payment_state\PaidAfterClosedTradePayment;
use fay\services\trade\payment_state\PaidTradePayment;
use fay\services\trade\payment_state\PaymentStateInterface;

class TradePaymentItem{
	/**
	 * @var PaymentStateInterface
	 */
	private $state;
	
	/**
	 * @var array 交易支付记录信息
	 */
	private $trade_payment;
	
	/**
	 * @var TradeItem 交易信息
	 */
	private $trade;
	
	/**
	 * @var array 支付方式信息
	 */
	private $payment;
	
	public function __construct($trade_payment_id){
		$trade_payment = TradePaymentsTable::model()->find($trade_payment_id);
		if(!$trade_payment){
			throw new TradeErrorException('交易支付记录不存在');
		}
		$this->trade_payment = $trade_payment;
		
		switch($trade_payment['status']){
			case TradePaymentsTable::STATUS_WAIT_PAY:
				$this->state = new CreateTradePayment();
				break;
			case TradePaymentsTable::STATUS_PAID:
				$this->state = new PaidTradePayment();
				break;
			case TradePaymentsTable::STATUS_CLOSED:
				$this->state = new ClosedTradePayment();
				break;
			case TradePaymentsTable::STATUS_PAID_AFTER_CLOSED:
				$this->state = new PaidAfterClosedTradePayment();
				break;
			default:
				throw new TradeErrorException('交易支付记录状态异常');
		}
	}
	
	public function setState(PaymentStateInterface $state){
		$this->state = $state;
	}
	
	/**
	 * 魔术方法获取交易信息
	 * @param string $name
	 * @return string
	 * @throws TradeErrorException
	 */
	public function __get($name){
		if(isset($this->trade_payment[$name])){
			return $this->trade_payment[$name];
		}else{
			throw new TradeErrorException("交易支付记录信息{$name}字段不存在");
		}
	}
	
	/**
	 * @return TradeItem
	 */
	public function getTrade(){
		//若未初始化，会根据交易记录里的trade_id初始化一个
		$this->trade || $this->trade = new TradeItem($this->trade_id);
		
		return $this->trade;
	}
	
	/**
	 * 设置交易信息。
	 * 为了解约开销，可以把交易信息传进来。
	 * 获取时若未初始化，会根据交易记录里的trade_id初始化一个
	 * @param TradeItem $trade
	 * @throws TradeErrorException
	 */
	public function setTrade(TradeItem $trade){
		if($this->trade_id != $trade->id){
			throw new TradeErrorException('设置交易信息不匹配');
		}
		$this->trade = $trade;
	}
	
	/**
	 * 获取支付方式。
	 * @return array
	 */
	public function getPayment(){
		//若未初始化，则根据交易记录中的payment_id初始化一个
		$this->payment || $this->payment = PaymentsTable::model()->find($this->payment_id);
		
		return $this->payment;
	}
	
	/**
	 * 设置支付方式。
	 * 为了节约开销，可以把支付方式信息传进来。
	 * 获取时若未初始化，会根据交易记录里的payment_id初始化一个。
	 * @param array $payment
	 * @throws TradeErrorException
	 */
	public function setPayment(array $payment){
		if($this->payment_id != $payment['id']){
			throw new TradeErrorException('设置支付方式不匹配');
		}
		$this->payment = $payment;
	}
	
	/**
	 * 获取交易支付记录外部交易号
	 */
	public function getOutTradeNo(){
		return date('Ymd') . NumberHelper::toLength($this->id, 7);
	}
	
	/**
	 * 接受支付回调
	 */
	public function onPaid(){
		//@todo 需要传入各种参数
		$this->state->onPaid($this);
	}
	
	public function pay(){
		$this->state->pay($this);
	}
}