<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use fay\services\oauth\OauthService;

class OauthController extends ApiController{
	public function weixin(){
		$user = OauthService::service()->getWeixinUser();
		
		var_dump($user);
	}
}