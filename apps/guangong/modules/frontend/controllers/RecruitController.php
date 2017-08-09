<?php

namespace guangong\modules\frontend\controllers;

use fay\helpers\ArrayHelper;
use cms\models\tables\RegionsTable;
use cms\services\OptionService;
use cms\services\user\UserService;
use cms\services\wechat\core\AccessToken;
use cms\services\wechat\jssdk\JsSDK;
use guangong\library\FrontController;
use guangong\models\forms\SignUpForm;
use guangong\models\tables\GuangongArmsTable;
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
        if($this->current_user){
            $this->view->user_extra = GuangongUserExtraTable::model()->find($this->current_user);
            $this->view->user = UserService::service()->get($this->current_user, 'id,mobile,avatar');
            $this->view->arm = GuangongArmsTable::model()->find($this->view->user_extra['arm_id']);
        }else{
            $this->view->user_extra = array();
            $this->view->user = array();
            $this->view->arm = array();
        }
        
        $this->view->states = ArrayHelper::column(RegionsTable::model()->fetchAll('parent_id = 1', 'id,name'), 'name', 'id');
        
        $this->form()->setModel(SignUpForm::model());
        
        $app_config = OptionService::getGroup('oauth:weixin');
        
        $js_sdk = new JsSDK($app_config['app_id'], $app_config['app_secret']);
        
        $access_token = new AccessToken($app_config['app_id'], $app_config['app_secret']);
        return $this->view->assign(array(
            'js_sdk_config'=>$js_sdk->getConfig(array('chooseImage', 'uploadImage')),
            'access_token'=>$access_token->getToken(),
        ));
        
        return $this->view->render();
    }
    
    public function step1(){
        
        return $this->view->render();
    }
    
    public function step2(){
//        $app_config = OptionService::getGroup('oauth:weixin');
//        $js_sdk = new JsSDK($app_config['app_id'], $app_config['app_secret']);
//        return $this->view->assign(array(
//            'js_sdk_config'=>$js_sdk->getConfig(array()),
//        ));
        
        return $this->view->render();
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
        
        return $this->view->render();
    }
    
    public function step4(){
        $this->view->user = UserService::service()->get($this->current_user, 'id,mobile');
        return $this->view->render();
    }
}