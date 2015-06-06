<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;
use fay\models\tables\Pages;
use fay\core\Validator;
use fay\core\HttpException;

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
			$page = Pages::model()->fetchRow(array(
				'alias = ?'=>$this->input->get('alias'),
			));
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