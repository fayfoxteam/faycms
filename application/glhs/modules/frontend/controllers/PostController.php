<?php
namespace glhs\modules\frontend\controllers;

use glhs\library\FrontController;
use fay\core\HttpException;
use fay\models\Post;
use fay\core\Validator;
use fay\models\Category;
use fay\core\Sql;
use fay\common\ListView;
use fay\models\tables\Posts;

class PostController extends FrontController{
	public function cat(){
		$validator = new Validator();
		if($validator->check(array(
			array('alias', 'required'),
		)) !== true){
			throw new HttpException('异常的请求', 404);
		}
		
		$cat = Category::model()->get($this->input->get('alias'));
		if(!$cat){
			throw new HttpException('文章不存在', 404);
		}

		$this->layout->title = $cat['title'];
		$this->layout->keywords = $cat['seo_keywords'];
		$this->layout->description = $cat['seo_keywords'];
		
		$sql = new Sql();
		$sql->from('posts', 'p', 'id,title,abstract,thumbnail,publish_time')
			->where(array(
				'p.cat_id = '.$cat['id'],
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISHED,
				'p.publish_time < '.$this->current_time,
			))
			->order('is_top DESC, sort, publish_time DESC');
		$this->view->assign(array(
			'cat'=>$cat,
			'listview'=>new ListView($sql, array(
				'reload'=>$this->view->url($cat['alias']),
				'page_size'=>10,
			)),
		))->render();
	}
	
	public function item(){
		$validator = new Validator();
		if($validator->check(array(
			array(array('id', 'cat'), 'required'),
			array(array('id'), 'numeric'),
		)) !== true){
			throw new HttpException('异常的请求', 404);
		}
		
		$id = $this->input->get('id', 'intval');
		$cat = Category::model()->get($this->input->get('cat'));
		
		$post = Post::model()->get($this->input->get('id', 'intval'), 'nav', $cat);
		if(!$post){
			throw new HttpException('文章不存在', 404);
		}
		$this->view->post = $post;
		
		//设置页面SEO信息
		$this->layout->title = $post['seo_title'];
		$this->layout->keywords = $post['seo_keywords'];
		$this->layout->description = $post['seo_description'];
		
		$this->layout->canonical = $this->view->url("{$cat['alias']}-{$post['id']}");
		
		$this->view->render();
	}
}