<?php
namespace fay\services\trade;

use fay\helpers\NumberHelper;
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
	 * @var array 新交易记录信息。
	 * 可以通过__set()方法修改对象属性值后，调用save()方法写入数据库
	 */
	private $new_trade_payment;
	
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
	 * 魔术方法获取交易支付信息字段（只允许获取trade_payments表存在的字段，否则会抛出一个异常）
	 * @param string $name
	 * @return string
	 * @throws TradeErrorException
	 */
	public function __get($name){
		if(isset($this->new_trade_payment[$name])){
			return $this->new_trade_payment[$name];
		}else if(isset($this->trade_payment[$name])){
			return $this->trade_payment[$name];
		}else{
			throw new TradeErrorException("交易支付记录信息{$name}字段不存在");
		}
	}
	
	/**
	 * 魔术方法设置交易支付信息字段（只允许获取trade_payments表存在的字段，否则会抛出一个异常）
	 * @param string $name
	 * @param string $value
	 * @throws TradeErrorException
	 */
	public function __set($name, $value){
		if(isset($this->trade_payment[$name])){
			$this->new_trade_payment[$name] = $value;
		}else{
			throw new TradeErrorException("交易支付记录信息{$name}字段不存在");
		}
	}
	
	/**
	 * 将通过__set()方法赋值的新字段值写入数据库。
	 * 若没有进行过赋值或字段值未改变，则直接返回true
	 * @return bool
	 */
	public function save(){
		if(!$this->new_trade_payment){
			//没有进行过赋值，直接返回true
			return true;
		}
		
		//记录被修改过的值
		$data = array();
		foreach($this->new_trade_payment as $name => $new_value){
			if($new_value != $this->trade_payment[$name]){
				$data[$name] = $new_value;
			}
		}
		
		if($data){
			//有值被修改，写入数据库
			TradePaymentsTable::model()->update($data, $this->id);
			return true;
		}else{
			//没有值被修改，直接返回true
			return true;
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
		$this->payment || $this->payment = PaymentMethodService::service()->get($this->payment_id);
		
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
	 * @param string $trade_no 第三方交易号
	 * @param string $payer_account 第三方付款帐号
	 * @param int $paid_fee 第三方回调时传过来的实付金额（单位：分）
	 */
	public function onPaid($trade_no, $payer_account, $paid_fee){
		//@todo 需要传入各种参数
		$this->state->onPaid($this, $trade_no, $payer_account, $paid_fee);
	}
	
	public function pay(){
		$this->state->pay($this);
	}
}