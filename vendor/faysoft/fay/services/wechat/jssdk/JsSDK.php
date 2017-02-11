<?php
namespace fay\services\wechat\jssdk;

use fay\caching\Cache;
use fay\helpers\StringHelper;

class JsSDK{
	/**
	 * 获取签名信息
	 * @param string $url
	 * @param string $app_id
	 * @param string $app_secret
	 * @param Cache|null $cache
	 * @return array
	 */
	public static function signature($url, $app_id, $app_secret, Cache $cache = null){
		$nonce = StringHelper::random();
		
		$ticket = new Ticket($app_id, $app_secret, $cache);
		
		return array(
			'appId'=> $app_id,
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
	public static function getSignature($ticket, $nonce, $url){
		return sha1("jsapi_ticket={$ticket}&noncestr={$nonce}&timestamp=".\F::app()->current_time."&url={$url}");
	}
}