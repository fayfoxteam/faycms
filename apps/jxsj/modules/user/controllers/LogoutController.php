<?php
namespace jxsj\modules\user\controllers;

use jxsj\library\UserController;
use fay\core\Response;
use cms\services\user\UserService;

class LogoutController extends UserController{
    public function index(){
        UserService::service()->logout();
        
        Response::redirect(null);
    }
}