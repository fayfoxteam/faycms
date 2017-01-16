<?php
namespace fay\payments;

/**
 * 支付方式配置信息
 */
class PaymentConfig{
	/**
	 * @var string 服务器异步通知页面路径
	 */
	private $notify_url;
	
	/**
	 * @var string 页面跳转同步通知页面路径
	 */
	private $return_url;
	
	/**
	 * @var string 签名方式（目前仅支付宝支持此参数）
	 */
	private $sign_type = 'MD5';
	
	/**
	 * @var string
	 *  - 在微信支付里对应：公众账号ID（app_id）
	 *  - 在支付宝支付里对应：合作者身份ID（partner）
	 *  - 在银联支付里没有这个值
	 */
	private $app_id;
	
	/**
	 * @var string
	 *  - 在微信支付里对应：商户号（mch_id）
	 *  - 在支付宝支付里对应：卖家支付宝用户号（seller_id）
	 *  - 在银联支付里对应：商户号（merId）
	 */
	private $mch_id;
	
	public function __construct($mch_id, $notify_url){
		$this->mch_id = $mch_id;
		$this->notify_url = $notify_url;
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
	 * @return PaymentConfig
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
	 * @return PaymentConfig
	 */
	public function setReturnUrl($return_url)
	{
		$this->return_url = $return_url;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getSignType()
	{
		return $this->sign_type;
	}
	
	/**
	 * @param string $sign_type
	 * @return PaymentConfig
	 */
	public function setSignType($sign_type)
	{
		$this->sign_type = $sign_type;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getAppId()
	{
		return $this->app_id;
	}
	
	/**
	 * @param string $app_id
	 * @return PaymentConfig
	 */
	public function setAppId($app_id)
	{
		$this->app_id = $app_id;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getMchId()
	{
		return $this->mch_id;
	}
	
	/**
	 * @param string $mch_id
	 * @return PaymentConfig
	 */
	public function setMchId($mch_id)
	{
		$this->mch_id = $mch_id;
		return $this;
	}
}