<?php
namespace jxsj\modules\frontend\controllers;

use jxsj\library\FrontController;
use fay\services\Post;
use fay\core\HttpException;
use fay\models\tables\PostMeta;

class PostController extends FrontController{
	public function item(){
		$post = Post::service()->get($this->input->get('id', 'intval'), 'nav.id,nav.title,files.*,category.title,meta.views,extra.*');
		
		if(!$post){
			throw new HttpException('页面不存在');
		}
		//阅读数
		PostMeta::model()->incr($post['post']['id'], 'views', 1);
		
		//seo
		$this->layout->title = $post['extra']['seo_title'];
		$this->layout->keywords = $post['extra']['seo_keywords'];
		$this->layout->description = $post['extra']['seo_description'];
		
		$this->view->post = $post;
		$this->view->render();
	}
	
}