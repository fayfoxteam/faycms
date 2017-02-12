<?php
namespace valentine\modules\frontend\controllers;

use fay\core\Http;
use fay\services\wechat\jssdk\JsSDK;
use valentine\library\FrontController;
use fay\services\OptionService;

class IndexController extends FrontController{
	public function index(){
		$this->layout->body_class = 'index';
		
		$app_config = OptionService::getGroup('oauth:weixin');
		
		$signature = JsSDK::signature(Http::getCurrentUrl(), $app_config['app_id'], $app_config['app_secret']);
		
		$this->view->assign(array(
			'signature'=>$signature,
		))->render();
	}
	
}