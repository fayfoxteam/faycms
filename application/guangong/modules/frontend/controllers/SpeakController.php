<?php
namespace guangong\modules\frontend\controllers;

use fay\helpers\ArrayHelper;
use fay\models\tables\RegionsTable;
use fay\services\OptionService;
use fay\services\user\UserService;
use fay\services\wechat\jssdk\JsSDK;
use guangong\library\FrontController;
use guangong\models\forms\SignUpForm;
use guangong\models\tables\GuangongArmsTable;
use guangong\models\tables\GuangongUserExtraTable;

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
		
		
		$this->view->renderPartial('create', array(
			'js_sdk_config'=>$js_sdk->getConfig(array('chooseImage', 'uploadImage')),
		));
	}
}