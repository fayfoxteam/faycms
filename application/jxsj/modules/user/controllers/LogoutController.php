<?php
namespace jxsj\modules\user\controllers;

use jxsj\library\UserController;
use fay\models\User;
use fay\core\Response;

class LogoutController extends UserController{
	public function index(){
		User::model()->logout();
		
		Response::redirect(null);
	}
}