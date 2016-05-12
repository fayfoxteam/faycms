<?php
namespace fay\widgets\post_item\controllers;

use fay\core\Widget;
use fay\models\Post;
use fay\core\HttpException;
use fay\core\db\Expr;
use fay\models\tables\PostMeta;

class IndexController extends Widget{
	private $fields = array(
		'post'=>array(
			'id', 'title', 'content', 'content_type', 'publish_time', 'thumbnail', 'abstract',
		),
		'category'=>array(
			'id', 'title', 'alias',
		),
		'categories'=>array(
			'id', 'title', 'alias',
		),
		'user'=>array(
			'id', 'username', 'nickname', 'avatar',
		),
		'nav'=>array(
			'id', 'title',
		),
		'tags'=>array(
			'id', 'title',
		),
		'files'=>array(
			'file_id', 'description', 'is_image',
		),
		'props'=>array(
			'*',//这里指定的是属性别名，取值视后台设定而定
		),
		'meta'=>array(
			'comments', 'views', 'likes',
		),
		'extra'=>array(
			'seo_title', 'seo_keywords', 'seo_description',
		)
	);
	
	public function getData($config){
		if(isset($config['fields'])){
			$fields = array(
				'post'=>$this->fields['post'],
				'extra'=>$this->fields['extra'],
			);
			foreach($config['fields'] as $f){
				$fields[$f] = $this->fields[$f];
			}
		}else{
			//若未配置，返回全部字段
			$fields = $this->fields;
		}
		
		if(!empty($config['id_key']) && $this->input->get($config['id_key'])){
			//有设置ID字段名，且传入ID字段
			$post = Post::model()->get($this->input->get($config['id_key'], 'intval'), $fields, isset($config['under_cat_id']) ? $config['under_cat_id'] : null);
			if(!$post){
				throw new HttpException('您访问的页面不存在');
			}
		}else{
			//未传入ID字段或未设置ID字段名
			$post = Post::model()->get($config['default_post_id'], $fields);
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
		if(isset($config['fields'])){
			$fields = $this->fields['post'];
			foreach($config['fields'] as $f){
				$fields[$f] = $this->fields[$f];
			}
		}else{
			//若未配置，返回全部字段
			$fields = $this->fields;
		}
		
		if(!empty($config['id_key']) && $this->input->get($config['id_key'])){
			//有设置ID字段名，且传入ID字段
			$post = Post::model()->get($this->input->get($config['id_key'], 'intval'), $fields, isset($config['under_cat_id']) ? $config['under_cat_id'] : null);
			if(!$post){
				throw new HttpException('您访问的页面不存在');
			}
			\F::app()->layout->title = $post['extra']['seo_title'];
			\F::app()->layout->keywords = $post['extra']['seo_keywords'];
			\F::app()->layout->description = $post['extra']['seo_description'];
		}else{
			//未传入ID字段或未设置ID字段名
			$post = Post::model()->get($config['default_post_id'], $fields);
			if(!$post){
				return '';
			}
		}
		
		//格式化文章内容
		$post['post']['content'] = Post::formatContent($post['post']);
		
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