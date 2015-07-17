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