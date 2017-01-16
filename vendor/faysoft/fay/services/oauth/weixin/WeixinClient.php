<?php
namespace fay\services\oauth\weixin;

use fay\core\Http;
use fay\helpers\HttpHelper;
use fay\helpers\StringHelper;
use fay\services\oauth\AccessTokenAbstract;
use fay\services\oauth\ClientAbstract;
use fay\services\oauth\OAuthException;

class WeixinClient extends ClientAbstract{
	/**
	 * 通过code换取网页授权access_token
	 */
	const ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token';
	
	/**
	 * 用户授权URL
	 */
	const AUTHORIZE_URL = 'https://open.weixin.qq.com/connect/oauth2/authorize';
	
	/**
	 * 获取授权URL
	 */
	public function getAuthorizeUrl(){
		if($this->state === null){
			$this->state = StringHelper::random();
		}
		
		$this->state_manager->setState($this->state);
		
		return HttpHelper::combineURL(self::AUTHORIZE_URL, array(
			'appid'=>$this->app_id,
			'redirect_uri'=>$this->redirect_uri ?: Http::getCurrentUrl(),
			'response_type'=>'code',
			'scope'=>$this->scope ?: 'snsapi_base',
			'state'=>$this->state
		));
	}
	
	/**
	 * @see \fay\services\oauth\ClientAbstract
	 * @param string $code
	 * @param null $state
	 * @return WeixinAccessToken
	 * @throws OAuthException
	 * @throws \fay\core\ErrorException
	 */
	public function getAccessToken($code, $state = null){
		$state || $state = \F::input()->get('state');
		
		if(!$this->state_manager->check($state)){
			throw new OAuthException('微信登录State参数异常');
		}
		
		$response = HttpHelper::getJson(self::ACCESS_TOKEN_URL, array(
			'appid'=>$this->app_id,
			'secret'=>$this->app_secret,
			'code'=>$code,
			'grant_type'=>'authorization_code',
		));
		
		if(isset($response['errcode'])){
			throw new OAuthException($response['errmsg'], $response['errcode']);
		}
		return new WeixinAccessToken($this->app_id, $response);
	}
}