<?php
namespace siwi\widgets\recent_posts\controllers;

use fay\widget\Widget;
use fay\services\CategoryService;
use fay\core\Sql;
use fay\models\tables\PostsTable;

class IndexController extends Widget{
	
	public function index($options){
		$cat_ids = CategoryService::service()->getChildIds('_blog');

		$sql = new Sql();
		$this->view->posts = $sql->from(array('p'=>'posts'), 'id,title,user_id,comments,thumbnail')
			->joinLeft(array('u'=>'users'), 'p.user_id = u.id', 'realname')
			->where(array(
				'p.cat_id IN (?)'=>$cat_ids,
				'p.delete_time = 0',
				'p.publish_time < '.$this->current_time,
				'p.status = '.PostsTable::STATUS_PUBLISHED,
			))
			->order('p.publish_time DESC')
			->limit(6)
			->fetchAll()
		;
		$this->view->render();
	}
}