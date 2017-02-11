<?php
namespace fay\services\wechat\jssdk;

use fay\caching\Cache;
use fay\core\ErrorException;
use fay\helpers\HttpHelper;
use fay\services\wechat\core\AccessToken;

/**
 * 通过Access Token获取Ticket
 */
class Ticket{
	/**
	 * 获取Token Url
	 */
	const TICKET_URL = 'https://api.weixin.qq.com/cgi-bin/token';
	
	/**
	 * @var string 应用ID
	 */
	protected $app_id;
	
	/**
	 * @var string 应用密钥
	 */
	protected $app_secret;
	
	/**
	 * @var string 缓存键前缀
	 */
	protected $cache_key_prefix = '.wechat.ticket.';
	
	/**
	 * @var string 缓存key（若不指定，默认返回$this->cache_key_prefix . $this->app_id）
	 */
	protected $cache_key;
	
	/**
	 * @var Cache 缓存实例
	 */
	protected $cache;
	
	/**
	 * @param string $app_id
	 * @param string $app_secret
	 * @param Cache|null $cache
	 */
	public function __construct($app_id, $app_secret, Cache $cache = null){
		$this->app_id = $app_id;
		$this->app_secret = $app_secret;
		$this->cache = $cache;
	}
	
	/**
	 * 获取Access Token
	 * @param bool $force_refresh 若为true，则强制刷新Access Token
	 * @return string
	 */
	public function getTicket($force_refresh = false){
		$cacheKey = $this->getCacheKey();
		$cached = $this->getCache()->get($cacheKey);
		
		if($force_refresh || empty($cached)){
			$ticket = $this->getTicketFromServer();
			
			//设置缓存（缓存时间会小于Access Token的过期时间，否则在临界点的时候可能导致图片无法显示等问题）
			$this->getCache()->set($cacheKey, $ticket['ticket'], $ticket['expires_in'] - 1500);
			
			return $ticket['ticket'];
		}
		return $cached;
	}
	
	/**
	 * 从微信服务器获取token
	 */
	protected function getTicketFromServer(){
		$ticket = HttpHelper::getJson(self::TICKET_URL, array(
			'access_token'=>$this->getToken(),
			'type'=>'jsapi',
		));
		
		if(empty($ticket['ticket'])){
			throw new ErrorException('获取微信Ticket失败，返回：' . json_encode($ticket, JSON_UNESCAPED_UNICODE));
		}
		
		return $ticket;
	}
	
	/**
	 * 设置缓存实例
	 * @param Cache $cache
	 * @return $this
	 */
	public function setCache(Cache $cache){
		$this->cache = $cache;
		
		return $this;
	}
	
	/**
	 * 获取缓存实例
	 * @return Cache
	 */
	public function getCache(){
		return $this->cache ?: \F::cache()->getDriver();
	}
	
	/**
	 * 获取缓存键
	 */
	protected function getCacheKey(){
		if(!$this->cache_key){
			return APPLICATION . $this->cache_key_prefix . $this->app_id;
		}
		
		return $this->cache_key;
	}
	
	/**
	 * 获取Access Token
	 * @return string
	 */
	protected function getToken(){
		$access_token = new AccessToken($this->app_id, $this->app_secret, $this->cache);
		return $access_token->getToken();
	}
}