<?php
namespace jxsj\modules\frontend\controllers;

use jxsj\library\FrontController;
use fay\models\Page;
use fay\models\tables\Pages;
use fay\core\HttpException;

class PageController extends FrontController{
	public function item(){
		$page = Page::model()->get($this->input->get('id', 'intval'));
		
		if(!$page){
			throw new HttpException('页面不存在');
		}
		//阅读数
		Pages::model()->incr($page['id'], 'views', 1);
		
		//seo
		$this->layout->title = $page['seo_title'];
		$this->layout->keywords = $page['seo_keywords'];
		$this->layout->description = $page['seo_description'];
		
		$this->view->page = $page;
		$this->view->render();
	}
	
}









