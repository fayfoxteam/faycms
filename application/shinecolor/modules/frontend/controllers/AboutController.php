<?php
namespace shinecolor\modules\frontend\controllers;

use shinecolor\library\FrontController;
use fay\core\Sql;

class AboutController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
		
		$this->layout->current_header_menu = 'about';
	}
	
	public function index(){
		$this->layout->title = '关于辉煌';
		
		$this->layout->breadcrumbs = array(
			array(
				'label'=>'首页',
				'link'=>$this->view->url(),
			),
			array(
				'label'=>'关于辉煌',
			),
		);
		
		$sql = new Sql();
		$this->view->pages = $sql->from('pages_categories', 'pc')
			->joinLeft('pages', 'p', 'pc.page_id = p.id', 'alias,title,content')
			->joinLeft('categories', 'c', 'pc.cat_id = c.id')
			->where(array('c.alias = ?'=>'about'))
			->order('p.sort')
			->fetchAll();
		
		$this->view->render();
	}
	
}