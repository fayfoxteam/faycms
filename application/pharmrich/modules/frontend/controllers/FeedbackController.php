<?php
namespace pharmrich\modules\frontend\controllers;

use pharmrich\library\FrontController;

class FeedbackController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->current_header_menu = 'feedback';
	}
	
	public function index(){
		
	}
	
	public function item(){
		$this->view->render();
	}
}