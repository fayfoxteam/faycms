<?php
namespace jxsj\modules\frontend\controllers;

use jxsj\library\FrontController;
use fay\services\Option;
use fay\services\Page;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
	}
	
	public function index(){
		$this->layout->keywords = Option::get('site:seo_index_keywords');
		$this->layout->description = Option::get('site:seo_index_description');
		
		$this->view->about = Page::service()->getByAlias('about');
		
		$this->view->render();
	}
	
}