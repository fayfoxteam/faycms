<?php
namespace jxsj2\modules\user\controllers;

use jxsj2\library\UserController;
use fay\core\Response;
use fay\services\UserService;

class LogoutController extends UserController{
	public function index(){
		UserService::service()->logout();
		
		Response::redirect(null);
	}
}