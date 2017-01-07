<?php
namespace ncp\modules\frontend\controllers;

use ncp\library\FrontController;
use fay\services\CategoryService;
use fay\core\Sql;
use fay\models\tables\PostsTable;
use fay\common\ListView;
use fay\services\PostService;
use fay\core\HttpException;
use fay\core\db\Expr;
use ncp\models\Recommend;
use fay\services\OptionService;

class NewsController extends FrontController{
	public function __construct(){
		parent::__construct();
	
		$this->layout->current_header_menu = 'news';
	}
	
	public function index(){
		if($this->form()->setRules(array(
			array('page', 'int'),
		))->setFilters(array(
			'page'=>'intval',
			'keywords'=>'trim',
		))->check()){
			$cat = CategoryService::service()->getByAlias('news');

			$this->layout->title = $cat['title'];
			$this->layout->keywords = $cat['seo_keywords'];
			$this->layout->description = $cat['seo_description'];
			
			$sql = new Sql();
			$sql->from(array('p'=>'posts'), 'id,title,thumbnail,abstract,publish_time')
				->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title')
				->where(array(
					'c.left_value >= '.$cat['left_value'],
					'c.right_value <= '.$cat['right_value'],
					'p.status = '.PostsTable::STATUS_PUBLISHED,
					'p.deleted = 0',
					'p.publish_time < '.$this->current_time,
				))
				->order('p.is_top DESC, p.sort, p.publish_time DESC')
			;
			
			if($keywords = $this->form()->getData('keywords')){
				$sql->where(array(
					'title LIKE ?'=>'%'.$keywords.'%',
				));
			}
			
			$this->view->listview = new ListView($sql, array(
				'page_size'=>10,
			));
			
			$product_cat = CategoryService::service()->getByAlias('product', 'id,left_value,right_value');//产品分类根目录
			$this->view->right_posts = RecommendTable::model()->getByCatAndArea($product_cat, 6, OptionService::get('site:right_recommend_days'));
			
			$this->view->render();
			
		}else{
			throw new HttpException('页面不存在');
		}
	}
	
	public function item(){
		$id = $this->input->get('id', 'intval');
		
		if(!$id || !$post = PostService::service()->get($id, 'nav.id,nav.title', 'news')){
			throw new HttpException('页面不存在');
		}
		PostsTable::model()->update(array(
			'last_view_time'=>$this->current_time,
			'views'=>new Expr('views + 1'),
		), $id);
		
		$this->layout->title = $post['seo_title'];
		$this->layout->keywords = $post['seo_keywords'];
		$this->layout->description = $post['seo_description'];
		
		$this->view->post = $post;
		
		$food_cat = CategoryService::service()->getByAlias('product', 'id,left_value,right_value');//产品分类根目录
		$this->view->right_posts = RecommendTable::model()->getByCatAndArea($food_cat, 6, OptionService::get('site:right_recommend_days'));
		
		$this->view->render();
	}
}