<?php
namespace siwi\widgets\recent_posts\controllers;

use fay\core\Widget;
use fay\models\Category;
use fay\core\Sql;
use fay\models\tables\Posts;

class IndexController extends Widget{
	
	public function index($options){
		$cat_ids = Category::model()->getAllIds('_blog');

		$sql = new Sql();
		$this->view->posts = $sql->from('posts', 'p', 'id,title,user_id,comments,thumbnail')
			->joinLeft('users', 'u', 'p.user_id = u.id', 'realname')
			->where(array(
				'p.cat_id IN (?)'=>$cat_ids,
				'p.deleted = 0',
				'p.publish_time < '.$this->current_time,
				'p.status = '.Posts::STATUS_PUBLISHED,
			))
			->order('p.publish_time DESC')
			->limit(6)
			->fetchAll()
		;
		$this->view->render();
	}
}