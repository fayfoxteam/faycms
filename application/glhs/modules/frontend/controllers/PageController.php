<?php
namespace glhs\modules\frontend\controllers;

use glhs\library\FrontController;
use fay\core\Validator;
use fay\core\HttpException;
use fay\models\Page;

class PageController extends FrontController{
	public function __construct(){
		parent::__construct();
	
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
	
		$this->layout->current_directory = 'home';
	}
	
	public function item(){
		$validator = new Validator();
		$check = $validator->check(array(
			array(array('alias'), 'required'),
		));
		
		if($check === true){
			$page = Page::model()->get($this->input->get('alias'));
			if($page){
				$this->view->page = $page;
				$this->layout->title = $page['seo_title'] ? $page['seo_title'] : $page['title'];
				$this->layout->keywords = $page['seo_keywords'];
				$this->layout->description = $page['seo_description'];
				$this->layout->current_directory = $page['alias'];
				$this->view->render();
			}else{
				throw new HttpException('别名不存在');
			}
		}else{
			throw new HttpException('参数异常', 500);
		}
	}
}