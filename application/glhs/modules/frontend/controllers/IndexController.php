<?php
namespace glhs\modules\frontend\controllers;

use glhs\library\FrontController;
use fay\models\Option;
use fay\models\tables\Pages;
use fay\models\Category;
use fay\models\Post;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '首页';
		$this->layout->keywords = Option::get('site:seo_index_keywords');
		$this->layout->description = Option::get('site:seo_index_description');
		
		$this->layout->current_directory = 'home';
	}
	
	public function index(){
		$this->view->render();
	}
	
}