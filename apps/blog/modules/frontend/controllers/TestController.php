<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;
use cms\services\user\UserService;

class TestController extends FrontController{
    public function devicemotion(){
        $this->view->renderPartial();
    }
    
    public function widgetArea(){
        \F::widget()->area('header');
    }

    //登录指定用户
    public function login(){
        UserService::service()->login($this->input->get('user_id', 'intval', 10001));
    }
}