<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\helpers\UrlHelper;
use fay\services\oauth\weixin\WeixinClient;

class OauthController extends ApiController{
	public function weixinGetCode(){
		$client = new WeixinClient('wxad76a044d8fad0ed', '88efdec5df431446c3c42a8ee4004b9d');
		$client->setRedirectUri(UrlHelper::createUrl('api/oauth/weixin-user-info'));
		
		header('location:' . $client->getAuthorizeUrl());
	}
	
	public function weixinUserInfo(){
		
	}
}