<?php
namespace valentine\modules\frontend\controllers;

use fay\core\Http;
use fay\services\wechat\jssdk\JsSDK;
use valentine\library\FrontController;
use fay\services\OptionService;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = OptionService::get('site:seo_index_title');
		$this->layout->keywords = OptionService::get('site:seo_index_keywords');
		$this->layout->description = OptionService::get('site:seo_index_description');
		
		$this->layout->current_directory = 'home';
	}
	
	public function index(){
		$app_config = OptionService::getGroup('oauth:weixin');
		
		$signature = JsSDK::signature(Http::getCurrentUrl(), $app_config['app_id'], $app_config['app_secret']);
		
		$this->view->assign(array(
			'signature'=>$signature,
		))->render();
	}
	
}