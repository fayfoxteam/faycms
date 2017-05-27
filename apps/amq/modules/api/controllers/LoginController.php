<?php
namespace amq\modules\api\controllers;

use cms\library\ApiController;
use fay\core\Response;

class LoginController extends ApiController{
    public function logout(){
        //直接销毁算了
        session_destroy();
        
        //跳转到首页
        Response::redirect();
    }
}