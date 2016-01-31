<?php
namespace fay\widgets\post_item\controllers;

use fay\core\Widget;
use fay\models\Post;
use fay\core\HttpException;
use fay\core\db\Expr;
use fay\models\tables\PostMeta;

class IndexController extends Widget{
	public function getData($config){
		//若未设置返回字段，初始化返回字段
		isset($config['fields']) || $config['fields'] = array(
			'user'=>array(
				'id', 'username', 'nickname', 'avatar',
			),
			'nav'=>array(
				'id', 'title',
			),
			'meta'=>array(
				'comments', 'views', 'likes',
			),
		);
		
		if(!empty($config['id_key']) && $this->input->get($config['id_key'])){
			//有设置ID字段名，且传入ID字段
			$post = Post::model()->get($this->input->get($config['id_key'], 'intval'), implode(',', $config['fields']), isset($config['under_cat_id']) ? $config['under_cat_id'] : null);
			if(!$post){
				throw new HttpException('您访问的页面不存在');
			}
		}else{
			//未传入ID字段或未设置ID字段名
			$post = Post::model()->get($config['default_post_id'], implode(',', $config['fields']));
			if(!$post){
				throw new HttpException('您访问的页面不存在');
			}
		}
		
		if($config['inc_views']){
			PostMeta::model()->update(array(
				'last_view_time'=>$this->current_time,
				'views'=>new Expr('views + 1'),
				'real_views'=>new Expr('real_views + 1'),
			), $post['post']['id']);
		}
		
		return $post;
	}
	
	public function index($config){
		//若未设置返回字段，初始化返回字段
		isset($config['fields']) || $config['fields'] = array(
			'user'=>array(
				'id', 'username', 'nickname', 'avatar',
			),
			'nav'=>array(
				'id', 'title',
			),
			'meta'=>array(
				'comments', 'views', 'likes',
			),
		);
		
		if(!empty($config['id_key']) && $this->input->get($config['id_key'])){
			//有设置ID字段名，且传入ID字段
			$post = Post::model()->get($this->input->get($config['id_key'], 'intval'), implode(',', $config['fields']), isset($config['under_cat_id']) ? $config['under_cat_id'] : null);
			if(!$post){
				throw new HttpException('您访问的页面不存在');
			}
			\F::app()->layout->title = $post['post']['seo_title'];
			\F::app()->layout->keywords = $post['post']['seo_keywords'];
			\F::app()->layout->description = $post['post']['seo_description'];
		}else{
			//未传入ID字段或未设置ID字段名
			$post = Post::model()->get($config['default_post_id'], implode(',', $config['fields']));
			if(!$post){
				return '';
			}
		}
		
		if($config['inc_views']){
			PostMeta::model()->update(array(
				'last_view_time'=>$this->current_time,
				'views'=>new Expr('views + 1'),
				'real_views'=>new Expr('real_views + 1'),
			), $post['post']['id']);
		}
		
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'post'=>$post,
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
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