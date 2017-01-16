<?php
namespace fay\payments;

/**
 * 包含必要的交易信息
 */
class PaymentTrade{
	/**
	 * @var string 商户订单号
	 */
	private $out_trade_no;
	
	/**
	 * @var int 以“分”为单位的整数金额
	 */
	private $total_fee;
	
	/**
	 * @var string 交易标题
	 */
	private $subject;
	
	/**
	 * @var string 交易描述
	 */
	private $body;
	
	/**
	 * @var string 商品展示网址
	 */
	private $show_url;
	
	/**
	 * @var string 超时时间
	 */
	private $it_b_pay;
	
	/**
	 * @var string 附加数据，透传字段
	 *  - 在微信支付里对应：attach
	 *  - 在银联支付里对应：reqReserved
	 *  - 在支付宝支付里没有这个值
	 */
	private $attach;
	
	/**
	 * @param string $out_trade_no
	 * @param int $total_fee 以“分”为单位的整数金额
	 */
	public function __construct($out_trade_no, $total_fee){
		$this->out_trade_no = $out_trade_no;
		$this->total_fee = $total_fee;
	}
	
	/**
	 * @return string
	 */
	public function getOutTradeNo()
	{
		return $this->out_trade_no;
	}
	
	/**
	 * @param string $out_trade_no
	 * @return PaymentTrade
	 */
	public function setOutTradeNo($out_trade_no)
	{
		$this->out_trade_no = $out_trade_no;
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getTotalFee()
	{
		return $this->total_fee;
	}
	
	/**
	 * @param int $total_fee
	 * @return PaymentTrade
	 */
	public function setTotalFee($total_fee)
	{
		$this->total_fee = $total_fee;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}
	
	/**
	 * @param string $subject
	 * @return PaymentTrade
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getBody()
	{
		return $this->body;
	}
	
	/**
	 * @param string $body
	 * @return PaymentTrade
	 */
	public function setBody($body)
	{
		$this->body = $body;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getShowUrl()
	{
		return $this->show_url;
	}
	
	/**
	 * @param string $show_url
	 * @return PaymentTrade
	 */
	public function setShowUrl($show_url)
	{
		$this->show_url = $show_url;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getItBPay()
	{
		return $this->it_b_pay;
	}
	
	/**
	 * @param string $it_b_pay
	 * @return PaymentTrade
	 */
	public function setItBPay($it_b_pay)
	{
		$this->it_b_pay = $it_b_pay;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getAttach()
	{
		return $this->attach;
	}
	
	/**
	 * @param string $attach
	 * @return PaymentTrade
	 */
	public function setAttach($attach)
	{
		$this->attach = $attach;
		return $this;
	}
}