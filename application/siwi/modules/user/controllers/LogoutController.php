<?php
namespace siwi\modules\user\controllers;

use siwi\library\UserController;
use fay\core\Response;
use fay\services\User;

class LogoutController extends UserController{
	public function index(){
		User::model()->logout();
		
		Response::redirect('index');
	}
}