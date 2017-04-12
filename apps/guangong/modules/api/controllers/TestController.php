<?php
namespace guangong\modules\api\controllers;

use cms\library\ApiController;
use cms\services\user\UserService;

/**
 * 测试Controller，上线前删除或禁用
 */
class TestController extends ApiController{
    //登录指定用户
    public function login(){
        UserService::service()->login($this->input->get('user_id', 'intval', 10001));
    }
    
    public function logout(){
        UserService::service()->logout();
    }
}