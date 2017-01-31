<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\models\tables\UserConnectsTable;
use fay\services\oauth\OAuthException;
use fay\services\oauth\OauthService;
use fay\services\OptionService;

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
		
		$user = OauthService::getInstance(
			UserConnectsTable::TYPE_WEIXIN,
			$config['app_id'],
			$config['app_secret']
		)->getUser();
		
		var_dump($user);
	}
}