<?php
namespace shinecolor\modules\frontend\controllers;

use shinecolor\library\FrontController;
use fay\services\Category;
use fay\core\Sql;
use fay\models\tables\Posts;
use fay\common\ListView;
use fay\services\Post;
use fay\helpers\Html;
use fay\core\HttpException;

class NewsController extends FrontController{
	public function __construct(){
		parent::__construct();
	
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
	
		$this->layout->current_header_menu = 'news';
	}
	
	public function index(){
		$cat_alias = $this->input->get('alias', 'news');
		$cat = Category::service()->getByAlias($cat_alias, '*');
		
		if(!$cat){
			throw new HttpException('404页面不存在');
		}
		
		if($cat['alias'] == 'news'){
			$this->layout->breadcrumbs = array(
				array(
					'label'=>'首页',
					'link'=>$this->view->url(),
				),
				array(
					'label'=>$cat['title'],
				),
			);
		}else{
			$this->layout->breadcrumbs = array(
				array(
					'label'=>'首页',
					'link'=>$this->view->url(),
				),
				array(
					'label'=>'新闻中心',
					'link'=>$this->view->url('news'),
				),
				array(
					'label'=>$cat['title'],
				),
			);
		}
		
		$this->view->cat = $cat;
		
		//获取news下的所有子节点
		$this->view->children = Category::service()->getChildren('news', 'alias,title');
		
		$sql = new Sql();
		$sql->from(array('p'=>'posts'), 'id,title,publish_time')
			->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id')
			->where(array(
				'c.left_value >= '.$cat['left_value'],
				'c.right_value <= '.$cat['right_value'],
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISHED,
				'p.publish_time < '.\F::app()->current_time,
			))
			->order('p.is_top DESC, p.sort, p.publish_time DESC')
		;
		
		$this->view->listview = new ListView($sql, array(
			'reload'=>$cat['alias'] == 'news' ? $this->view->url('news') : $this->view->url('news/'.$cat['alias']),
			'page_size'=>10,
		));
		
		$this->view->render();
	}
	
	public function item(){
		$id = $this->input->get('id', 'intval');
		$post = Post::service()->get($id);
		
		if(!$post){
			throw new HttpException('404页面不存在');
		}
		
		$this->view->children = Category::service()->getChildren('news');
		$this->view->cat = Category::service()->get($post['cat_id']);
		
		$this->layout->breadcrumbs = array(
			array(
				'label'=>'首页',
				'link'=>$this->view->url(),
			),
			array(
				'label'=>$this->view->cat['title'],
				'link'=>$this->view->cat['alias'] == 'news' ? $this->view->url('news') : $this->view->url('news/'.$this->view->cat['alias']),
			),
			array(
				'label'=>$post['title'],
			),
		);
		
		$this->layout->title = $post['seo_title'];
		$this->layout->keywords = Html::encode($post['seo_keywords']);
		$this->layout->description = $post['seo_description'];
		
		$this->view->post = $post;
		
		$this->view->render();
	}
}