<?php
namespace guangong\modules\frontend\controllers;

use fay\services\OptionService;
use fay\services\wechat\core\AccessToken;
use fay\services\wechat\jssdk\JsSDK;
use guangong\library\FrontController;

/**
 * 天下招募令
 */
class SpeakController extends FrontController{
	public function __construct()
	{
		parent::__construct();
		
		$this->checkLogin();
		$this->layout->title = '军团活动';
	}
	
	public function index(){
		
		$this->view->renderPartial();
	}
	
	public function shared(){
		
		$this->view->renderPartial();
	}
	
	public function create(){
		$app_config = OptionService::getGroup('oauth:weixin');
		
		$js_sdk = new JsSDK($app_config['app_id'], $app_config['app_secret']);
		
		$access_token = new AccessToken($app_config['app_id'], $app_config['app_secret']);
		$this->view->renderPartial('create', array(
			'js_sdk_config'=>$js_sdk->getConfig(array('chooseImage', 'uploadImage')),
			'access_token'=>$access_token,
		));
	}
}