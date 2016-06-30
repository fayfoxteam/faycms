<?php
namespace uuice\modules\doc\controllers;

use uuice\library\DocController;
use fay\core\Sql;
use fay\services\Post;
use fay\services\Option;

class IndexController extends DocController{
	public function index(){
		$this->layout->title = Option::get('site:sitename');
		$this->layout->page_title = Option::get('site:sitename');
		
		$sql = new Sql();
		$sql->from(array('p'=>'posts'), 'cat_id')
			->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'alias,title,description')
			->order('last_modified_time DESC')
			->limit(20)
			->group('p.cat_id')
		;
		$this->view->last_modified_cats = $sql->fetchAll();
		
		$this->view->assign(array(
			'posts'=>Post::service()->getByCatAlias('fayfox', 0, 'id,title,content,content_type', false, 'is_top DESC, sort, publish_time ASC'),
		))->render();
	}
	
}