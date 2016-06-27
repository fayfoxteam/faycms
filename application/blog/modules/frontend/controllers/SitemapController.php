<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;
use fay\models\tables\Posts;
use fay\services\Category;

class SitemapController extends FrontController{
	public function xml(){
		$this->layout_template = false;
		header('Content-type: text/xml');
		$this->view->posts = Posts::model()->fetchAll(array(
			'deleted = 0',
			"publish_time < {$this->current_time}",
			'status = '.Posts::STATUS_PUBLISHED,
		), 'id,title,publish_time', 'publish_time DESC');
		
		$this->view->cats = Category::service()->getNextLevel('_system_post');
		$this->view->render();
	}
	
	public function html(){
		
	}
}