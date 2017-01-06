<?php
namespace siwi\modules\user\controllers;

use siwi\library\UserController;
use fay\core\Response;
use fay\services\UserService;

class LogoutController extends UserController{
	public function index(){
		UserService::service()->logout();
		
		Response::redirect('index');
	}
}