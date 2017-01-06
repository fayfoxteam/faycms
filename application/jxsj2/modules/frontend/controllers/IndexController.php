<?php
namespace jxsj2\modules\frontend\controllers;

use jxsj2\library\FrontController;
use fay\services\OptionService;
use fay\services\PageService;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
	}
	
	public function index(){
		$this->layout->keywords = OptionService::get('site:seo_index_keywords');
		$this->layout->description = OptionService::get('site:seo_index_description');
		
		$this->view->about = PageService::service()->getByAlias('about');
		
		$this->view->render();
	}
	
}