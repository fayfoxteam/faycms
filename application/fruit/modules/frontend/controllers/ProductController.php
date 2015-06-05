<?php
namespace fruit\modules\frontend\controllers;

use fruit\library\FrontController;
use fay\models\Category;
use fay\core\Sql;
use fay\models\tables\Posts;
use fay\common\ListView;
use fay\models\Post;
use fay\core\HttpException;
use fay\core\db\Intact;

class ProductController extends FrontController{
	public function __construct(){
		parent::__construct();
	
		$this->layout->current_header_menu = 'product';
	}
	
	public function index(){
		$this->layout->title = '产品中心';
		
		$this->view->cats = Category::model()->getAll('product');
		
		if($this->input->get('cat')){
			$cat = Category::model()->getByAlias($this->input->get('cat'));
		}else{
			$cat = Category::model()->getByAlias('product');
		}
		$this->view->cat = $cat;
		
		$sql = new Sql();
		$sql->from('posts', 'p', 'id,title,thumbnail,abstract')
			->joinLeft('categories', 'c', 'p.cat_id = c.id')
			->where(array(
				'p.deleted = 0',
				'p.publish_time < '.$this->current_time,
				'p.status = '.Posts::STATUS_PUBLISHED,
				'c.left_value >= '.$cat['left_value'],
				'c.right_value <= '.$cat['right_value'],
			))
			->order('p.is_top DESC, p.sort, p.publish_time DESC')
		;
		
		$this->view->listview = new ListView($sql, array(
			'reload'=>$cat['alias'] == 'product' ? $this->view->url('product') : $this->view->url('product/'.$cat['alias']),
			'page_size'=>10,
		));
		
		$this->view->render();
	}
	
	public function item(){
		$id = $this->input->get('id', 'intval');
		
		$post = Post::model()->get($id, 'nav', 'product');
		
		if(!$post){
			throw new HttpException('页面不存在');
		}
		Posts::model()->update(array(
			'last_view_time'=>$this->current_time,
			'views'=>new Intact('views + 1'),
		), $id);
		$this->view->post = $post;
		
		$this->layout->title = $post['seo_title'];
		$this->layout->keywords = $post['seo_keywords'];
		$this->layout->description = $post['seo_description'];
		
		$this->view->cats = Category::model()->getAll('product');
		
		$this->view->render();
	}
}