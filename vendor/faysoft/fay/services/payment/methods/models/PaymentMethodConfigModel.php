<?php
namespace fay\services\payment\methods\models;

use fay\services\payment\methods\PaymentMethodException;

/**
 * 支付方式配置信息
 */
class PaymentMethodConfigModel{
	/**
	 * @var string 支付编码。例如（weixin:jsapi）这样的格式
	 */
	private $code;
	
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
	
	/**
	 * @var string 商户支付密钥
	 */
	private $key;
	
	/**
	 * @var string 公众帐号secert（微信JSAPI支付有这个参数）
	 */
	private $app_secret;
	
	/**
	 * @param string $code
	 */
	public function __construct($code){
		$this->code = $code;
	}
	
	/**
	 * 判断传入字段是否都有值（不同支付方式，必选字段有所不同）。
	 * 返回不满足条件的空字段一维数组，若返回空数组，代表验证成功。
	 * @param array $fields
	 * @param string $payment 支付访问，用于报错时明确错误
	 * @return array
	 * @throws PaymentMethodException
	 */
	public function checkRequiredField($fields, $payment){
		$empty_fields = array();
		foreach($fields as $f){
			if(!$this->{$f}){
				$empty_fields[] = $f;
			}
		}
		
		if($empty_fields){
			throw new PaymentMethodException($payment . '配置：字段[' . implode(', ', $empty_fields) . ']不能为空');
		}
		
		return true;
	}
	
	/**
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}
	
	/**
	 * @param string $code
	 * @return PaymentMethodConfigModel
	 */
	public function setCode($code)
	{
		$this->code = $code;
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
	 * @return PaymentMethodConfigModel
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
	 * @return PaymentMethodConfigModel
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
	 * @return PaymentMethodConfigModel
	 */
	public function setMchId($mch_id)
	{
		$this->mch_id = $mch_id;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}
	
	/**
	 * @param string $key
	 * @return PaymentMethodConfigModel
	 */
	public function setKey($key)
	{
		$this->key = $key;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getAppSecret()
	{
		return $this->app_secret;
	}
	
	/**
	 * @param string $app_secret
	 * @return PaymentMethodConfigModel
	 */
	public function setAppSecret($app_secret)
	{
		$this->app_secret = $app_secret;
		return $this;
	}
}