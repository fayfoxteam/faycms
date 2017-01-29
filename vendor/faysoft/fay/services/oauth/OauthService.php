<?php
namespace fay\services\oauth;

use fay\core\Response;
use fay\core\Service;
use fay\services\oauth\weixin\WeixinClient;
use fay\services\OptionService;

/**
 * @todo 后期可以按登录方式再做一层拆分
 */
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
	 * @param string $scope
	 *  - snsapi_base 只能获取openId，不需要用户授权，用户无感知
	 *  - snsapi_userinfo 可以获取到用户昵称，头像等信息，需要用户授权
	 * @return weixin\WeixinAccessToken
	 * @throws OAuthException
	 */
	public function getWeixinAccessToken($scope = 'snsapi_base'){
		$config = $this->getConfig('oauth:weixin');
	
		$client = new WeixinClient($config['app_id'], $config['app_secret']);
		if(!$code = \F::input()->get('code')){
			//需要获取用户信息（默认为：snsapi_base）
			$client->setScope($scope);
		
			//跳转到微信拉取授权
			Response::redirect($client->getAuthorizeUrl());
		}
	
		return $client->getAccessToken($code);
	}
	
	/**
	 * 获取微信用户信息
	 * @return array
	 * @throws OAuthException
	 */
	public function getWeixinUser(){
		return $this->getWeixinAccessToken('snsapi_userinfo')->getUser();
	}
	
	/**
	 * 获取openId
	 * @return string
	 */
	public function getWeixinOpenId(){
		return $this->getWeixinAccessToken()->getOpenId();
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