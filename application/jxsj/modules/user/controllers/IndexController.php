<?php
namespace jxsj\modules\user\controllers;

use jxsj\library\UserController;

class IndexController extends UserController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->current_directory = 'home';
	}
	
	public function index(){
		
		
		$this->view->render();
	}
	
}