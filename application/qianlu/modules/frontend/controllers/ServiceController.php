<?php
namespace qianlu\modules\frontend\controllers;

use qianlu\library\FrontController;
use fay\services\Category;
use fay\core\Sql;
use fay\models\tables\Posts;
use fay\services\Post;

class ServiceController extends FrontController{
	public $layout_template = 'inner';
	
	public function index(){
		$this->layout->current_directory = 'service';
		$this->layout->subtitle = '服务介绍';
		
		//团队
		$cat_service = Category::service()->getByAlias('service', '*');
		//SEO
		$this->layout->title = $cat_service['seo_title'];
		$this->layout->keywords = $cat_service['seo_keywords'];
		$this->layout->description = $cat_service['seo_description'];
		
		$sql = new Sql();
		$services = $sql->from(array('p'=>'posts'), 'id,title')
			->order('p.is_top DESC, p.sort, p.publish_time DESC')
			->where(array(
				'p.cat_id = '.$cat_service['id'],
				'p.deleted = 0',
				"p.publish_time < {$this->current_time}",
				'p.status = '.Posts::STATUS_PUBLISHED,
			))
			->fetchAll();
		;
		$submenu = array(
			array(
				'title'=>'服务介绍',
				'class'=>'sel',
				'link'=>$this->view->url('service'),
			),
		);
		foreach($services as $p){
			$submenu[] = array(
				'title'=>$p['title'],
				'link'=>$this->view->url('service/'.$p['id']),
			);
		}
		
		$this->layout->submenu = $submenu;
		
		if($this->input->get('id')){
			$post = Post::service()->get($this->input->get('id', 'intval'));
		}else{
			$post = Post::service()->get($services[0]['id']);
		}
		
		$this->layout->breadcrumbs = array(
			array(
				'title'=>'首页',
				'link'=>$this->view->url(),
			),
			array(
				'title'=>'服务介绍',
				'link'=>$this->view->url('service'),
			),
			array(
				'title'=>$post['title'],
			),
		);
		$this->view->post = $post;
		
		
		$this->view->render();
	}
}