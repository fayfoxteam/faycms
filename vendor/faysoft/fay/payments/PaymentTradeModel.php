<?php
namespace fay\payments;

/**
 * 包含必要的交易信息
 */
class PaymentTradeModel{
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
	 * @var string 订单生成时间（strtotime能识别的时间格式都行）
	 */
	private $time_start;
	
	/**
	 * @var string 订单失效时间（strtotime能识别的时间格式都行）
	 */
	private $time_expire;
	
	/**
	 * @var string 服务器异步通知页面路径
	 */
	private $notify_url;
	
	/**
	 * @var string 页面跳转同步通知页面路径
	 */
	private $return_url;
	
	/**
	 * @var int 交易支付记录ID
	 * （并不属于支付需要用到的字段，但是做微信支付OAuth认证的时候需要做跳转，要用到这个字段）
	 */
	private $trade_payment_id;
	
	/**
	 * 判断传入字段是否都有值（不同支付方式，必选字段有所不同）。
	 * 返回不满足条件的空字段一维数组，若返回空数组，代表验证成功。
	 * @param array $fields
	 * @param string $payment 支付访问，用于报错时明确错误
	 * @return array
	 * @throws PaymentException
	 */
	public function checkRequiredField($fields, $payment){
		$empty_fields = array();
		foreach($fields as $f){
			if(!$this->{$f}){
				$empty_fields[] = $f;
			}
		}
		
		if($empty_fields){
			throw new PaymentException($payment . '交易参数：字段[' . implode(', ', $empty_fields) . ']不能为空');
		}
		
		return true;
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
	 * @return PaymentTradeModel
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
	 * @return PaymentTradeModel
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
	 * @return PaymentTradeModel
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
	 * @return PaymentTradeModel
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
	 * @return PaymentTradeModel
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
	 * @return PaymentTradeModel
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
	 * @return PaymentTradeModel
	 */
	public function setAttach($attach)
	{
		$this->attach = $attach;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getTimeStart()
	{
		return $this->time_start;
	}
	
	/**
	 * @param string $time_start
	 * @return PaymentTradeModel
	 */
	public function setTimeStart($time_start)
	{
		$this->time_start = $time_start;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getTimeExpire()
	{
		return $this->time_expire;
	}
	
	/**
	 * @param string $time_expire
	 * @return PaymentTradeModel
	 */
	public function setTimeExpire($time_expire)
	{
		$this->time_expire = $time_expire;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getNotifyUrl()
	{
		return $this->notify_url;
	}
	
	/**
	 * @param string $notify_url
	 * @return PaymentTradeModel
	 */
	public function setNotifyUrl($notify_url)
	{
		$this->notify_url = $notify_url;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getReturnUrl()
	{
		return $this->return_url;
	}
	
	/**
	 * @param string $return_url
	 * @return PaymentTradeModel
	 */
	public function setReturnUrl($return_url)
	{
		$this->return_url = $return_url;
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getTradePaymentId()
	{
		return $this->trade_payment_id;
	}
	
	/**
	 * @param int $trade_payment_id
	 * @return PaymentTradeModel
	 */
	public function setTradePaymentId($trade_payment_id)
	{
		$this->trade_payment_id = $trade_payment_id;
		return $this;
	}
}