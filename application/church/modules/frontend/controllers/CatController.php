<?php
namespace church\modules\frontend\controllers;

use church\library\FrontController;
use fay\core\HttpException;
use fay\services\Category;

class CatController extends FrontController{
	public function item(){
		$cat_alias = $this->input->get('cat_alias', 'trim');
		if(!$cat_alias || !$cat = Category::service()->get($cat_alias)){
			throw new HttpException('您请求的页面不存在');
		}
		
		$this->layout->assign(array(
			'page_title'=>$cat['title'],
		));
		
		$this->view->render();
	}
}