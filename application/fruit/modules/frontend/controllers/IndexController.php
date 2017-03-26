<?php
namespace fruit\modules\frontend\controllers;

use fay\services\post\PostCategoryService;
use fruit\library\FrontController;
use fay\models\tables\PagesTable;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
		
		$this->layout->current_header_menu = 'home';
	}
	
	public function index(){
		$this->view->about = PagesTable::model()->fetchRow("alias = 'about'", 'title,thumbnail,abstract,content');
		
		$this->view->products = PostCategoryService::service()->getPosts('product', 7, 'id,title,thumbnail', true);

		$this->view->case_1 = PagesTable::model()->fetchRow("alias = 'case-1'", 'id,title,abstract');
		$this->view->case_2 = PagesTable::model()->fetchRow("alias = 'case-2'", 'id,title,abstract');
		$this->view->case_3 = PagesTable::model()->fetchRow("alias = 'case-3'", 'id,title,abstract');
		
		$this->view->render();
	}
	
}