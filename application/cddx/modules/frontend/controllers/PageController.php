<?php
namespace cddx\modules\frontend\controllers;

use cddx\library\FrontController;
use fay\models\tables\Pages;
use fay\core\HttpException;
use fay\models\Category;

class PageController extends FrontController{
	public function __construct(){
		parent::__construct();
	}
	
	public function item(){
		if($this->input->get('alias')){
			$page = Pages::model()->fetchRow(array('alias = ?'=>$this->input->get('alias')));
		}else if($this->input->get('id')){
			$page = Pages::model()->fetchRow(array('id = ?'=>$this->input->get('id', 'intval')));
		}
		
		if(isset($page) && $page){
			Pages::model()->incr($page['id'], 'views', 1);
			$this->view->page = $page;
		}else{
			throw new HttpException('您请求的页面不存在');
		}
		
		$this->layout->title = $page['title'];
		$this->layout->keywords = $page['seo_keywords'] ? $page['seo_keywords'] : $page['title'];
		$this->layout->description = $page['seo_description'] ? $page['seo_description'] : $page['abstract'];
		
		$root_cat = Category::model()->getByAlias('_system_post');
		$left_cats = $root_cat;
		$child_cats = Category::model()->getTreeByParentId($root_cat['id']);
		$left_cats['children'] = $child_cats;
		$this->view->left_cats = $left_cats;

		$this->view->render();
	}
}