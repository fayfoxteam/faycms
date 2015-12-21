<?php
namespace pharmrich\modules\frontend\controllers;

use pharmrich\library\FrontController;
use fay\core\HttpException;
use fay\models\Category;

class ProductController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->current_header_menu = 'products';
	}
	
	public function index(){
		$cat_id = $this->input->get('cat_id', 'intval');
		
		if($cat_id){
			$cat = Category::model()->get($cat_id);
			if(!$cat){
				throw new HttpException('您请求的页面不存在');
			}
		}else{
			$cat = Category::model()->get('products');
		}
		
		$this->view->cat = $cat;
		
		$this->view->render();
	}
	
	public function item(){
		$this->view->render();
	}
}