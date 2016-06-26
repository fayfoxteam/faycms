<?php
namespace cddx2\modules\frontend\controllers;

use cddx2\library\FrontController;
use fay\services\Option;
use fay\services\Menu;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = Option::get('site:seo_index_title');
		$this->layout->keywords = Option::get('site:seo_index_keywords');
		$this->layout->description = Option::get('site:seo_index_description');
		
		$this->layout->current_directory = 'home';
	}
	
	public function index(){
		$this->view->menus = Menu::service()->getTree('_user_menu');
		
		$this->view->render();
	}
	
}