<?php
namespace jxsj\modules\frontend\controllers;

use jxsj\library\FrontController;
use fay\models\Post;
use fay\models\tables\Posts;
use fay\core\HttpException;

class PostController extends FrontController{
	public function item(){
		$post = Post::model()->get($this->input->get('id', 'intval'), 'nav,files');
		
		if(!$post){
			throw new HttpException('页面不存在');
		}
		//阅读数
		Posts::model()->inc($post['id'], 'views', 1);
		
		//seo
		$this->layout->title = $post['seo_title'];
		$this->layout->keywords = $post['seo_keywords'];
		$this->layout->description = $post['seo_description'];
		
		$this->view->post = $post;
		$this->view->render();
	}
	
}









