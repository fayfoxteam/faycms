<?php
namespace blog\widgets\rand_posts\controllers;

use fay\core\Widget;
use fay\models\tables\Posts;
use fay\core\Sql;

class IndexController extends Widget{
	
	public function index($options){
		$sql = new Sql();
		$this->view->posts = $sql->from('posts', 'p', 'id,title,publish_time,comments')
			->where(array(
				'deleted = 0',
				"publish_time < {$this->current_time}",
				'status = '.Posts::STATUS_PUBLISHED,
			))
			->order('RAND()')
			->limit(5)
			->fetchAll();
		
		$this->view->render();
	}
}