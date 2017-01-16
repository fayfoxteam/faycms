<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;
use fay\helpers\UrlHelper;
use fay\oauth\qq\QQClient;
use fay\oauth\weixin\WeixinClient;

class OauthController extends FrontController{
	public function weixinGetCode(){
		$client = new WeixinClient('wxfe1a287248f99621', 'ce5b4c1c736ef50c980ab8212e44169d');
		$client->setRedirectUri(UrlHelper::createUrl('oauth/weixin-user-info'));
		$client->setScope('snsapi_userinfo');//需要获取用户信息
		
		header('location:' . $client->getAuthorizeUrl());
	}
	
	public function weixinUserInfo(){
		
	}
	
	public function qqGetCode(){
		$client = new QQClient('100317529', '3bb44788f9ebdd4f35aba1edda24287a');
		$client->setRedirectUri(UrlHelper::createUrl('oauth/qq-user-info'));
		
		header('location:' . $client->getAuthorizeUrl());
	}
	
	public function qqUserInfo(){
		
	}
}