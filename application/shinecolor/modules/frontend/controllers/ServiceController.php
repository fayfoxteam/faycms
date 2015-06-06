<?php
namespace shinecolor\modules\frontend\controllers;

use shinecolor\library\FrontController;
use fay\models\tables\Pages;
use fay\helpers\Html;
use fay\core\Sql;
use fay\core\HttpException;

class ServiceController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
		
		$this->layout->current_header_menu = 'service';
	}
	
	public function item(){
		$alias = $this->input->get('alias');
		if(!$alias){
			throw new HttpException('未设置别名');
		}
		$page = Pages::model()->fetchRow(array(
			'alias = ?'=>$alias,
		));
		if(!$page){
			throw new HttpException('别名不存在');
		}
		
		$this->view->page = $page;
		
		$this->layout->title = Html::encode($page['title']);
		
		$sql = new Sql();
		$this->view->pages = $sql->from('pages_categories', 'pc')
			->joinLeft('pages', 'p', 'pc.page_id = p.id', 'alias,title')
			->joinLeft('categories', 'c', 'pc.cat_id = c.id')
			->where(array(
				"c.alias = 'service'",
				'p.deleted = 0',
			))
			->order('p.sort')
			->fetchAll();
		
		$this->layout->breadcrumbs = array(
			array(
				'label'=>'首页',
				'link'=>$this->view->url(),
			),
			array(
				'label'=>Html::encode($page['title']),
			),
		);
		$this->view->render();
	}
}