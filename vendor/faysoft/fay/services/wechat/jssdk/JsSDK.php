<?php
namespace fay\services\wechat\jssdk;

use fay\caching\Cache;
use fay\core\Http;
use fay\helpers\StringHelper;

class JsSDK{
	/**
	 * @var string 应用ID
	 */
	protected $app_id;
	
	/**
	 * @var string 应用密钥
	 */
	protected $app_secret;
	
	/**
	 * @param string $app_id
	 * @param string $app_secret
	 */
	public function __construct($app_id, $app_secret){
		$this->app_id = $app_id;
		$this->app_secret = $app_secret;
	}
	
	/**
	 * 获取签名信息
	 * @param string $url
	 * @param Cache|null $cache
	 * @return array
	 */
	public function signature($url = null, Cache $cache = null){
		$url || $url = Http::getCurrentUrl();
		$nonce = StringHelper::random();
		
		$ticket = new Ticket($this->app_id, $this->app_secret, $cache);
		
		return array(
			'appId'=> $this->app_id,
			'timestamp'=>\F::app()->current_time,
			'nonceStr'=>$nonce,
			'signature'=>self::getSignature($ticket->getTicket(), $nonce, $url),
		);
	}
	
	/**
	 * 获取签名值
	 * @param string $ticket
	 * @param string $nonce
	 * @param string $url
	 * @return string
	 */
	private function getSignature($ticket, $nonce, $url){
		return sha1("jsapi_ticket={$ticket}&noncestr={$nonce}&timestamp=".\F::app()->current_time."&url={$url}");
	}
	
	/**
	 * @param array $APIs
	 * @param bool $debug
	 * @param bool $beta
	 * @param bool $json
	 * @return array|string
	 */
	public function getConfig(array $APIs, $debug = false, $beta = false, $json = true){
		$config = $this->signature();
		$config['debug'] = $debug;
		$config['beta'] = $beta;
		$config['jsApiList'] = $APIs;
		
		return $json ? json_encode($config) : $config;
	}
}