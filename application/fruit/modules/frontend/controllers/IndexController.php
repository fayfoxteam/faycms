<?php
namespace fruit\modules\frontend\controllers;

use fruit\library\FrontController;
use fay\models\tables\Pages;
use fay\models\Post;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
		
		$this->layout->current_header_menu = 'home';
	}
	
	public function index(){
		$this->view->about = Pages::model()->fetchRow("alias = 'about'", 'title,thumbnail,abstract,content');
		
		$this->view->products = Post::model()->getByCatAlias('product', 7, 'id,title,thumbnail', true);

		$this->view->case_1 = Pages::model()->fetchRow("alias = 'case-1'", 'id,title,abstract');
		$this->view->case_2 = Pages::model()->fetchRow("alias = 'case-2'", 'id,title,abstract');
		$this->view->case_3 = Pages::model()->fetchRow("alias = 'case-3'", 'id,title,abstract');
		
		$this->view->render();
	}
	
}