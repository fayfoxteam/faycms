<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\services\oauth\OauthService;

class OauthController extends ApiController{
	public function weixin(){
		$access_token = OauthService::service()->getWeixinAccessToken();
		$user = $access_token->getUser();
		
		var_dump($user);
	}
}