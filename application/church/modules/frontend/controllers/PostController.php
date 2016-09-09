<?php
namespace church\modules\frontend\controllers;

use church\library\FrontController;
use fay\core\HttpException;
use fay\services\Category;

class PostController extends FrontController{
	public function index(){
		$cat_id = $this->input->get('cat_id', 'intval');
		
		if($cat_id){
			$cat = Category::service()->get($cat_id);
			if(!$cat){
				throw new HttpException('您请求的页面不存在');
			}
		}else{
			$cat = Category::service()->get('products');
		}
		
		$this->view->cat = $cat;
		
		$this->view->render();
	}
	
	public function item(){
		$this->view->render();
	}
}