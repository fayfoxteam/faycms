<?php
namespace fay\services\oauth;

use fay\core\Response;
use fay\core\Service;
use fay\services\oauth\weixin\WeixinClient;
use fay\services\OptionService;

class OauthService extends Service{
	/**
	 * @param string $class_name
	 * @return OauthService
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 获取微信Access Token
	 * @return \fay\services\oauth\weixin\WeixinAccessToken
	 * @throws OAuthException
	 */
	public function getWeixinAccessToken(){
		$config = $this->getConfig('oauth:weixin');
	
		$client = new WeixinClient($config['app_id'], $config['app_secret']);
		if(!$code = \F::input()->get('code')){
			//需要获取用户信息（默认为：snsapi_base）
			$client->setScope('snsapi_userinfo');
		
			//跳转到微信拉取授权
			Response::redirect($client->getAuthorizeUrl());
		}
	
		return $client->getAccessToken($code);
	}
    
    /**
     * 获取指定OAuth配置参数
     * @param string $key
     * @return array
     * @throws OAuthException
     */
	private function getConfig($key){
		$config = OptionService::getGroup($key);
		if(!$config){
			throw new OAuthException("{{$key}}Oauth参数未设置");
		}
	
		if(empty($config['enabled'])){
			throw new OAuthException("{{$key}}Oauth登录已禁用");
		}
        
        return $config;
	}
}