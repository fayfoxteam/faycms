<?php
namespace fay\widgets\category_posts\controllers;

use fay\widget\Widget;
use fay\services\Category;
use fay\services\Post;
use fay\helpers\Date;

class IndexController extends Widget{
	public function getData($config){
		$conditions = array();
		
		//root node
		if(empty($config['top'])){
			$root_node = Category::service()->getByAlias('_system_post', 'id');
			$config['top'] = $root_node['id'];
		}
		
		//number
		empty($config['number']) && $config['number'] = 5;
		
		//date format
		empty($config['date_format']) && $config['date_format'] = '';
		
		//thumbnail
		empty($config['thumbnail']) || $conditions[] = 'thumbnail != 0';
		
		//last view
		empty($config['last_view_time']) ||
			$conditions[] = 'last_view_time > '.(\F::app()->current_time - 86400 * $config['last_view_time']);
		
		isset($config['fields']) || $config['fields'] = array('user', 'category', 'meta');
		
		//order
		$orders = array(
			'hand'=>'is_top DESC, sort, publish_time DESC',
			'publish_time'=>'publish_time DESC',
			'views'=>'views DESC, publish_time DESC',
			'rand'=>'RAND()',
		);
		if(!empty($config['order']) && isset($orders[$config['order']])){
			$order = $orders[$config['order']];
		}else{
			$order = $orders['hand'];
		}
		
		if(!isset($config['subclassification'])){
			$config['subclassification'] = true;
		}
		
		$fields = array(
			'posts'=>array('id', 'title', 'user_id', 'thumbnail', 'publish_time', 'abstract'),
		);
		if(in_array('user', $config['fields'])){
			$fields['user'] = array('id', 'username', 'nickname', 'avatar');
		}
		if(in_array('category', $config['fields'])){
			$fields['category'] = array('id', 'title', 'alias');
		}
		if(in_array('meta', $config['fields'])){
			$fields['meta'] = array('views', 'likes', 'comments');
		}
		$posts = Post::service()->getByCatId($config['top'], $config['number'], $fields, $config['subclassification'], $order, $conditions);
		if($posts){
			foreach($posts as &$p){
				if($config['date_format'] == 'pretty'){
					$p['post']['format_publish_time'] = Date::niceShort($p['post']['publish_time']);
				}else if($config['date_format']){
					$p['post']['format_publish_time'] = \date($config['date_format'], $p['post']['publish_time']);
				}else{
					$p['post']['format_publish_time'] = '';
				}
				
				$p['post']['link'] = $this->view->url(str_replace('{$id}', $p['post']['id'], $config['uri']));
			}
		}
		return $posts;
	}
	
	public function index($config){
		$conditions = array();
		
		//root node
		if(empty($config['top'])){
			$root_node = Category::service()->getByAlias('_system_post', 'id');
			$config['top'] = $root_node['id'];
		}
		
		//title
		if(empty($config['title'])){
			$node = Category::service()->get($config['top'], 'title');
			$config['title'] = $node['title'];
		}
		
		//uri
		empty($config['uri']) && $config['uri'] = 'post/{$id}';
		
		//number
		empty($config['number']) && $config['number'] = 5;
		
		//show_empty
		isset($config['show_empty']) || $config['show_empty'] = 0;
		
		//date format
		empty($config['date_format']) && $config['date_format'] = '';
		
		//thumbnail
		empty($config['thumbnail']) || $conditions[] = 'thumbnail != 0';
		
		//last view
		empty($config['last_view_time']) ||
			$conditions[] = 'last_view_time > '.(\F::app()->current_time - 86400 * $config['last_view_time']);
		
		isset($config['fields']) || $config['fields'] = array('user', 'category', 'meta');
		
		//order
		$orders = array(
			'hand'=>'is_top DESC, sort, publish_time DESC',
			'publish_time'=>'publish_time DESC',
			'views'=>'views DESC, publish_time DESC',
			'rand'=>'RAND()',
		);
		if(!empty($config['order']) && isset($orders[$config['order']])){
			$order = $orders[$config['order']];
		}else{
			$order = $orders['hand'];
		}
		
		if(!isset($config['subclassification'])){
			$config['subclassification'] = true;
		}
		
		$fields = array(
			'posts'=>array('id', 'title', 'user_id', 'thumbnail', 'publish_time', 'abstract'),
		);
		if(in_array('user', $config['fields'])){
			$fields['user'] = array('id', 'username', 'nickname', 'avatar');
		}
		if(in_array('category', $config['fields'])){
			$fields['category'] = array('id', 'title', 'alias');
		}
		if(in_array('meta', $config['fields'])){
			$fields['meta'] = array('views', 'likes', 'comments');
		}
		$posts = Post::service()->getByCatId($config['top'], $config['number'], $fields, $config['subclassification'], $order, $conditions);
		
		//若无文章可显示，则不显示该widget
		if(empty($posts) && !$config['show_empty']){
			return;
		}
		
		foreach($posts as &$p){
			if($config['date_format'] == 'pretty'){
				$p['post']['format_publish_time'] = Date::niceShort($p['post']['publish_time']);
			}else if($config['date_format']){
				$p['post']['format_publish_time'] = \date($config['date_format'], $p['post']['publish_time']);
			}else{
				$p['post']['format_publish_time'] = '';
			}
				
			$p['post']['link'] = $this->view->url(str_replace('{$id}', $p['post']['id'], $config['uri']));
		}
		
		//template
		if(empty($config['template'])){
			//调用默认模版
			$this->view->render('template', array(
				'posts'=>$posts,
				'config'=>$config,
				'alias'=>$this->alias,
				'_index'=>$this->_index,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
				//调用app的view文件
				\F::app()->view->renderPartial($config['template'], array(
					'posts'=>$posts,
					'config'=>$config,
					'alias'=>$this->alias,
					'_index'=>$this->_index,
				));
			}else{
				//直接视为代码执行
				$alias = $this->view->alias;
				$_index = $this->_index;
				eval('?>'.$config['template'].'<?php ');
			}
		}
		
	}
}