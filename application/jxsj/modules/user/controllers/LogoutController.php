<?php
namespace jxsj\modules\user\controllers;

use jxsj\library\UserController;
use fay\core\Response;
use fay\services\User;

class LogoutController extends UserController{
	public function index(){
		User::model()->logout();
		
		Response::redirect(null);
	}
}