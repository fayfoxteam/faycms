<?php
namespace jxsj2\modules\user\controllers;

use jxsj2\library\UserController;
use fay\core\Response;
use fay\services\User;

class LogoutController extends UserController{
	public function index(){
		User::model()->logout();
		
		Response::redirect(null);
	}
}