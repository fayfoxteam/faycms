<?php
namespace fruit\modules\frontend\controllers;

use fruit\library\FrontController;
use fay\services\Category;

class ProductController extends FrontController{
	public function __construct(){
		parent::__construct();
	
		$this->layout->current_header_menu = 'product';
	}
	
	public function index(){
		
		$this->view->render();
	}
	
	public function item(){
		$this->view->cats = Category::service()->getChildren('product');
		
		$this->view->render();
	}
}