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
	
	/**
	 * @var array 新交易信息。
	 * 可以通过__set()方法修改对象属性值后，调用save()方法写入数据库
	 */
	private $new_trade;
	
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
	 * 魔术方法获取交易信息字段（只允许获取trades表存在的字段，否则会抛出一个异常）
	 * @param string $name
	 * @return string
	 * @throws TradeErrorException
	 */
	public function __get($name){
		if(isset($this->new_trade[$name])){
			return $this->new_trade[$name];
		}else if(isset($this->trade[$name])){
			return $this->trade[$name];
		}else{
			throw new TradeErrorException("交易信息{$name}字段不存在");
		}
	}
	
	/**
	 * 魔术方法设置交易信息字段（只允许获取trades表存在的字段，否则会抛出一个异常）
	 * @param string $name
	 * @param string $value
	 * @throws TradeErrorException
	 */
	public function __set($name, $value){
		if(isset($this->trade[$name])){
			$this->new_trade[$name] = $value;
		}else{
			throw new TradeErrorException("交易信息{$name}字段不存在");
		}
	}
	
	/**
	 * 将通过__set()方法赋值的新字段值写入数据库。
	 * 若没有进行过赋值或字段值未改变，则直接返回true
	 * @return bool
	 */
	public function save(){
		if(!$this->new_trade){
			//没有进行过赋值，直接返回true
			return true;
		}
		
		//记录被修改过的值
		$data = array();
		foreach($this->new_trade as $name => $new_value){
			if($new_value != $this->trade[$name]){
				$data[$name] = $new_value;
			}
		}
		
		if($data){
			//有值被修改，写入数据库
			TradesTable::model()->update($data, $this->id);
			return true;
		}else{
			//没有值被修改，直接返回true
			return true;
		}
	}
}