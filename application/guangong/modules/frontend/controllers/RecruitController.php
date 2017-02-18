<?php

namespace guangong\modules\frontend\controllers;

use fay\helpers\ArrayHelper;
use fay\models\tables\RegionsTable;
use fay\services\OptionService;
use fay\services\user\UserService;
use fay\services\wechat\jssdk\JsSDK;
use guangong\library\FrontController;
use guangong\models\forms\SignUpForm;
use guangong\models\tables\GuangongUserExtraTable;

/**
 * 天下招募令
 */
class RecruitController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->checkLogin();
		$this->layout->title = '天下招募令';
	}
	
	public function index(){
		$this->view->render();
	}
	
	public function step1(){
		
		$this->view->render();
	}
	
	public function step2(){
//		$app_config = OptionService::getGroup('oauth:weixin');
//		$js_sdk = new JsSDK($app_config['app_id'], $app_config['app_secret']);
//		$this->view->assign(array(
//			'js_sdk_config'=>$js_sdk->getConfig(array()),
//		));
		
		$this->view->render();
	}
	
	public function step3(){
		if($this->current_user){
			$this->view->user_extra = GuangongUserExtraTable::model()->find($this->current_user);
			$this->view->user = UserService::service()->get($this->current_user, 'id,mobile,avatar');
		}else{
			$this->view->user_extra = array();
			$this->view->user = array();
		}
		
		$this->view->states = ArrayHelper::column(RegionsTable::model()->fetchAll('parent_id = 1', 'id,name'), 'name', 'id');
		
		$this->form()->setModel(SignUpForm::model());
		
		$this->view->render();
	}
	
	public function step4(){
		$this->view->user = UserService::service()->get($this->current_user, 'id,mobile');
		$this->view->render();
	}
}