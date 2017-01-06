<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;
use fay\services\OptionService;
use fay\core\Sql;
use fay\models\tables\Posts;
use fay\common\ListView;
use fay\services\CategoryService;
use fay\core\HttpException;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = OptionService::get('site:seo_index_title');
		$this->layout->keywords = OptionService::get('site:seo_index_keywords');
		$this->layout->description = OptionService::get('site:seo_index_description');
		
		$this->layout->current_directory = 'home';
	}
	
	public function index(){
		$sql = new Sql();
		$sql->from(array('p'=>'posts'))
			->joinLeft(array('c'=>'categories'), 'p.cat_id = c.id', 'title AS cat_title')
			->where(Posts::getPublishedConditions('p'))
			->order('p.is_top DESC, p.sort, p.publish_time DESC')
		;
		
		$reload = $this->view->url();
		if($this->input->get('cat')){
			$cat = CategoryService::service()->get($this->input->get('cat', 'intval'));
			if(!$cat){
				throw new HttpException('分类不存在');
			}
			$sql->where(array(
				'c.left_value >= '.$cat['left_value'],
				'c.right_value <= '.$cat['right_value'],
			));
			$this->view->subtitle = '分类目录归档： '.$cat['title'];
			$this->layout->title = $cat['title'];
			$reload = $this->view->url('cat/'.$cat['id']);
		}
		
		if($this->input->get('type')){
			if($this->input->get('type') == 'post'){
				$reload = $this->view->url('post');
				$cat = CategoryService::service()->getByAlias('_blog');
				$this->layout->title = '博文';
				$this->layout->current_directory = 'blog';
			}else if($this->input->get('type') == 'work'){
				$reload = $this->view->url('work');
				$cat = CategoryService::service()->getByAlias('_work');
				$this->layout->title = '作品';
				$this->layout->current_directory = 'work';
			}
			$sql->where(array(
				'c.left_value >= '.$cat['left_value'],
				'c.right_value <= '.$cat['right_value'],
			));
		}
		
		$this->view->listview = new ListView($sql, array(
			'reload'=>$reload,
		));
		$this->view->listview->init();
		
		if($this->view->listview->current_page > 1){
			$this->layout->canonical = $reload.'?page='.$this->view->listview->current_page;
		}else{
			$this->layout->canonical = $reload;
		}
		
		$this->view->work_cat = CategoryService::service()->getByAlias('_work');
		
		$this->view->render();
	}
	
}