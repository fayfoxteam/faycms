<?php
namespace church\modules\frontend\controllers;

use church\library\FrontController;
use fay\services\Page;

class ContactController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->current_directory = 'contact';
	}
	
	public function index(){
		$page = Page::service()->get('contact');
		
		$this->layout->assign(array(
			'page_title'=>$page['title'],
			'show_banner'=>false,
			'title'=>$page['seo_title'],
			'keywords'=>$page['seo_keywords'],
			'description'=>$page['seo_description'],
		));
		
		$this->view->assign(array(
			'page'=>$page,
		))->render();
	}
}