<?php
namespace church\modules\frontend\controllers;

use church\library\FrontController;
use fay\core\HttpException;
use fay\services\CategoryService;

class CatController extends FrontController{
	public function item(){
		$cat = $this->input->get('cat', 'trim');
		if(!$cat || !$cat = CategoryService::service()->get($cat)){
			throw new HttpException('您请求的页面不存在');
		}
		
		$this->layout->assign(array(
			'page_title'=>$cat['title'],
		));
		
		$this->view->render();
	}
}