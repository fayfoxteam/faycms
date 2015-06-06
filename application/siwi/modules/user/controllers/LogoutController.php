<?php
namespace siwi\modules\user\controllers;

use siwi\library\UserController;
use fay\models\User;
use fay\core\Response;

class LogoutController extends UserController{
	public function index(){
		User::model()->logout();
		
		Response::redirect('index');
	}
}