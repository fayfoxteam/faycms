<?php
namespace siwi\modules\user\controllers;

use siwi\library\UserController;
use fay\core\Response;
use cms\services\user\UserService;

class LogoutController extends UserController{
    public function index(){
        UserService::service()->logout();
        
        $this->response->redirect('index');
    }
}