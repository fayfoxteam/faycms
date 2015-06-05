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
		$this->layout->keywords = Option::get('site.seo_index_keywords');
		$this->layout->description = Option::get('site.seo_index_description');
		
		$this->layout->current_directory = 'home';
	}
	
	public function index(){
		//关于我们
		$this->view->about = Pages::model()->fetchRow("alias = 'about'", '!content');
		
		//师资力量分类
		$this->view->cat_teacher = Category::model()->get('teacher', 'description');
		//师资力量文章
		$this->view->teachers = Post::model()->getByCat('teacher', 4, 'id,title,thumbnail');
		
		//画室资讯
		$this->view->news = Post::model()->getByCat('news', 8, 'id,title,publish_time');
		
		//学生作品
		$this->view->works = Post::model()->getByCat('works', 6, 'id,title,thumbnail');
		
		$this->view->render();
	}
	
}