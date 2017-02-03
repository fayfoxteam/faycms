<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\models\tables\UserConnectsTable;
use fay\services\oauth\OAuthException;
use fay\services\oauth\OauthService;
use fay\services\OptionService;
use fay\services\user\UserOauthService;
use fay\services\user\UserService;

class OauthController extends ApiController{
	public function weixin(){
		$key = 'oauth:weixin';
		$config = OptionService::getGroup($key);
		if(!$config){
			throw new OAuthException("{{$key}} Oauth参数未设置");
		}
		
		if(empty($config['enabled'])){
			throw new OAuthException("{{$key}} Oauth登录已禁用");
		}
		
		$oauth_user = OauthService::getInstance(
			UserConnectsTable::TYPE_WEIXIN,
			$config['app_id'],
			$config['app_secret']
		)
			->getAccessToken()//获取Access Token
			->getUser();
		$user_id = UserOauthService::service()
			->createUser($oauth_user);
		
		UserService::service()->login($user_id);
		
		Response::redirect('recruit/step3');
	}
}