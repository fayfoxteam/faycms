<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;
use fay\services\oauth\weixin\WeixinClient;

class OauthController extends ApiController{
	public function weixin(){
		$app_id = 'wxad76a044d8fad0ed';
		$app_secret = '88efdec5df431446c3c42a8ee4004b9d';
		
		$client = new WeixinClient($app_id, $app_secret);
		if(!$code = $this->input->get('code')){
			//需要获取用户信息（默认为：snsapi_base）
			$client->setScope('snsapi_userinfo');
			
			//跳转到微信拉取授权
			Response::redirect($client->getAuthorizeUrl());
		}
		
		$access_token = $client->getAccessToken($code);
		
		dump($access_token->getUser());
	}
}