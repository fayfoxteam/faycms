<?php
namespace pharmrich\modules\frontend\controllers;

use pharmrich\library\FrontController;
use fay\core\HttpException;
use fay\models\Category;

class ProductController extends FrontController{
	public function index(){
		$cat_id = $this->input->get('cat_id', 'intval');
		
		//获取分类
		if(!$cat_id || !$cat = Category::model()->get($cat_id)){
			throw new HttpException('您请求的页面不存在');
		}
		
		$this->view->cat = $cat;
		
		$this->view->render();
	}
	
	public function item(){
		$this->view->render();
	}
}