<?php
namespace cddx2\modules\frontend\controllers;

use cddx2\library\FrontController;
use fay\models\Option;
use fay\models\Category;
use fay\models\Menu;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = Option::get('site:seo_index_keywords');
		$this->layout->keywords = Option::get('site:seo_index_keywords');
		$this->layout->description = Option::get('site:seo_index_description');
		
		$this->layout->current_directory = 'home';
	}
	
	public function index(){
		$this->view->menus = Menu::model()->getTree('_user_menu');
		
		$this->view->render();
	}
	
}