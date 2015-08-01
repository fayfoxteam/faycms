<?php
namespace fay\widgets\post_item\controllers;

use fay\core\Widget;
use fay\models\Post;
use fay\core\HttpException;
use fay\models\tables\Posts;

class IndexController extends Widget{
	public function index($config){
		isset($config['fields']) || $config['fields'] = array('user', 'nav');
		
		if(!empty($config['id_key']) && $this->input->get($config['id_key'])){
			$post = Post::model()->get($this->input->get($config['id_key'], 'intval'), implode(',', $config['fields']), isset($config['under_cat_id']) ? $config['under_cat_id'] : null);
			if(!$post){
				throw new HttpException('您访问的页面不存在');
			}
			\F::app()->layout->title = $post['seo_title'];
			\F::app()->layout->keywords = $post['seo_keywords'];
			\F::app()->layout->description = $post['seo_description'];
		}else{
			$post = Post::model()->get($config['default_post_id'], implode(',', $config['fields']));
			if(!$post){
				return '';
			}
		}
		
		if($config['inc_views']){
			Posts::model()->inc($post['id'], 'views', 1);
		}
		
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'post'=>$post,
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'post'=>$post,
					'config'=>$config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				eval('?>'.$config['template'].'<?php ');
			}
		}
		
	}
}