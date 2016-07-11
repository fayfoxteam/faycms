<?php
namespace qianlu\modules\frontend\controllers;

use qianlu\library\FrontController;
use fay\models\tables\Pages;
use fay\services\Category;
use fay\core\Sql;
use fay\models\tables\Posts;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
	}
	
	public function index(){
		//关于我们
		$this->view->about = Pages::model()->fetchRow(array('alias = ?'=>'about'), 'abstract');
		
		//资讯
		$cat_post = Category::service()->getByAlias('post', 'left_value,right_value');
		$sql = new Sql();
		$this->view->last_news = $sql->from(array('p'=>'posts'), 'id,title,publish_time,abstract,cat_id')
			->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title')
			->where(array(
				'c.left_value > '.$cat_post['left_value'],
				'c.right_value < '.$cat_post['right_value'],
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISHED,
				'p.publish_time < '.$this->current_time,
			))
			->order('p.is_top DESC, p.sort, p.publish_time DESC')
			->limit(5)
			->fetchAll();
			
		$this->view->render();
	}
	
}








