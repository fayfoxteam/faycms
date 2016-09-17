<?php
namespace fay\widgets\post_item\controllers;

use fay\widget\Widget;
use fay\services\Post;
use fay\core\HttpException;
use fay\core\db\Expr;
use fay\models\tables\PostMeta;

class IndexController extends Widget{
	private $fields = array(
		'post'=>array(
			'fields'=>array(
				'id', 'title', 'content', 'content_type', 'publish_time', 'thumbnail', 'abstract',
			)
		),
		'category'=>array(
			'fields'=>array(
				'id', 'title', 'alias',
			)
		),
		'categories'=>array(
			'fields'=>array(
				'id', 'title', 'alias',
			)
		),
		'user'=>array(
			'fields'=>array(
				'id', 'username', 'nickname', 'avatar',
			)
		),
		'nav'=>array(
			'fields'=>array(
				'id', 'title',
			)
		),
		'tags'=>array(
			'fields'=>array(
				'id', 'title',
			)
		),
		'files'=>array(
			'fields'=>array(
				'*',
			)
		),
		'props'=>array(
			'fields'=>array(
				'*',//这里指定的是属性别名，取值视后台设定而定
			)
		),
		'meta'=>array(
			'fields'=>array(
				'comments', 'views', 'likes',
			)
		),
		'extra'=>array(
			'fields'=>array(
				'seo_title', 'seo_keywords', 'seo_description',
			)
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
		
		//文章缩略图
		if(!empty($config['post_thumbnail_width']) || !empty($config['post_thumbnail_height'])){
			$fields['post']['extra'] = array(
				'thumbnail'=>(empty($config['post_thumbnail_width']) ? 0 : $config['post_thumbnail_width']) .
					'x' .
					(empty($config['post_thumbnail_height']) ? 0 : $config['post_thumbnail_height']),
			);
		}
		
		//附件缩略图
		if(!empty($config['file_thumbnail_width']) || !empty($config['file_thumbnail_height'])){
			$fields['files']['extra'] = array(
				'thumbnail'=>(empty($config['file_thumbnail_width']) ? 0 : $config['file_thumbnail_width']) .
					'x' .
					(empty($config['file_thumbnail_height']) ? 0 : $config['file_thumbnail_height']),
			);
		}
		
		if(!empty($config['id_key']) && $this->input->get($config['id_key'])){
			//有设置ID字段名，且传入ID字段
			$post = Post::service()->get($this->input->get($config['id_key'], 'intval'), $fields, isset($config['under_cat_id']) ? $config['under_cat_id'] : null);
			if(!$post){
				throw new HttpException('您访问的页面不存在');
			}
		}else{
			//未传入ID字段或未设置ID字段名
			$post = Post::service()->get($config['default_post_id'], $fields);
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
		
		//文章缩略图
		if(!empty($config['post_thumbnail_width']) || !empty($config['post_thumbnail_height'])){
			$fields['post']['extra'] = array(
				'thumbnail'=>(empty($config['post_thumbnail_width']) ? 0 : $config['post_thumbnail_width']) .
					'x' .
					(empty($config['post_thumbnail_height']) ? 0 : $config['post_thumbnail_height']),
			);
		}
		
		//附件缩略图
		if(!empty($config['file_thumbnail_width']) || !empty($config['file_thumbnail_height'])){
			$fields['files']['extra'] = array(
				'thumbnail'=>(empty($config['file_thumbnail_width']) ? 0 : $config['file_thumbnail_width']) .
					'x' .
					(empty($config['file_thumbnail_height']) ? 0 : $config['file_thumbnail_height']),
			);
		}
		
		if(!empty($config['id_key']) && $this->input->get($config['id_key'])){
			//有设置ID字段名，且传入ID字段
			$post = Post::service()->get($this->input->get($config['id_key'], 'intval'), $fields, isset($config['under_cat_id']) ? $config['under_cat_id'] : null);
			if(!$post){
				throw new HttpException('您访问的页面不存在');
			}
			
			\F::app()->layout->assign(array(
				'title'=>$post['extra']['seo_title'],
				'keywords'=>$post['extra']['seo_keywords'],
				'description'=>$post['extra']['seo_description'],
			));
		}else{
			//未传入ID字段或未设置ID字段名
			$post = Post::service()->get($config['default_post_id'], $fields);
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