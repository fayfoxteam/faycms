<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;
use fay\core\HttpException;
use fay\services\PostService;
use fay\helpers\StringHelper;

class PostController extends FrontController{
	public function __construct(){
		parent::__construct();
	
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
	
		$this->layout->current_directory = 'blog';
	}
	
	public function item(){
		//auth request
		if(!$this->input->get('id') || !StringHelper::isInt($this->input->get('id'))){
			throw new HttpException('异常的请求');
		}
		
		$post = PostService::service()->get($this->input->get('id', 'intval'), 'extra.*,nav.*,categories.*,category.*');
		if(!$post){
			throw new HttpException('文章不存在', 404);
		}
		$this->view->post = $post;
		
		//@todo 高亮作品tab
		
		//设置页面SEO信息
		$this->layout->title = $post['extra']['seo_title'];
		$this->layout->keywords = $post['extra']['seo_keywords'];
		$this->layout->description = $post['extra']['seo_description'];
		
		$this->layout->qr_data = 'http://m.fayfox.com/post/'.$post['post']['id'];
		
		$this->layout->canonical = $this->view->url('post/'.$post['post']['id']);
		
		$this->view->render();
	}
}