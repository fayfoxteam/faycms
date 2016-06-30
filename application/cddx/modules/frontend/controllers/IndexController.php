<?php
namespace cddx\modules\frontend\controllers;

use cddx\library\FrontController;
use fay\services\Page;
use fay\services\Category;
use fay\services\Post;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
		
		$this->layout->current_header_menu = 'home';
	}
	
	public function index(){
		$page_about = Page::service()->getByAlias('about');
		$cat_news = Category::service()->getByAlias('news');
		$news = Post::service()->getByCat($cat_news, 6, 'id,title,abstract,publish_time', true);
		
		$this->view->assign(array(
			'about'=>$page_about,
			'cat_news'=>$cat_news,
			'news'=>$news,
		))->render();
	}
	
}