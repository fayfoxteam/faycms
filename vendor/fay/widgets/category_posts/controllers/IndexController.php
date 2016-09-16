<?php
namespace fay\widgets\category_posts\controllers;

use fay\widget\Widget;
use fay\services\Category;
use fay\services\Post;
use fay\helpers\Date;
use fay\services\post\Category as PostCategory;

class IndexController extends Widget{
	/**
	 * 返回字段
	 */
	private $fields = array(
		'post'=>array(
			'fields'=>array(
				'id', 'cat_id', 'title', 'publish_time', 'user_id', 'is_top', 'thumbnail', 'abstract'
			)
		),
		'user'=>array(
			'fields'=>array(
				'id', 'username', 'nickname', 'avatar'
			)
		),
		'meta'=>array(
			'fields'=>array(
				'comments', 'views', 'likes'
			)
		),
		'files'=>array(
			'fields'=>array(
				'id', 'description', 'url', 'thumbnail', 'is_image'
			)
		),
		'category'=>array(
			'fields'=>array(
				'id', 'title', 'alias'
			)
		),
		'tags'=>array(
			'fields'=>array(
				'id', 'title',
			)
		),
	);
	
	/**
	 * 排序方式
	 */
	private $order_map = array(
		'hand'=>'is_top DESC, sort, publish_time DESC',
		'publish_time'=>'publish_time DESC',
		'views'=>'views DESC, publish_time DESC',
	);
	
	/**
	 * 配置信息
	 */
	private $config;
	
	public function getData($config){
		$this->initConfig($config);
		
		$conditions = $this->getConditions();
		
		$fields = $this->getFields();
		$order = $this->getOrder();
		
		$posts = PostCategory::service()->getPosts(
			$this->config['top'],
			$this->config['number'],
			$fields,
			$this->config['subclassification'],
			$order,
			$conditions
		);
		
		return $posts;
	}
	
	public function index($config){
		$this->initConfig($config);
		
		$conditions = $this->getConditions();
		
		$fields = $this->getFields();
		$order = $this->getOrder();
		
		$posts = PostCategory::service()->getPosts(
			$this->config['top'],
			$this->config['number'],
			$fields,
			$this->config['subclassification'],
			$order,
			$conditions
		);
		
		//若无文章可显示，则不显示该widget
		if(empty($posts) && !$this->config['show_empty']){
			return;
		}
		
		$posts = $this->formatPosts($posts);
		
		//template
		if(empty($this->config['template'])){
			//调用默认模版
			$this->view->render('template', array(
				'posts'=>$posts,
				'config'=>$this->config,
				'alias'=>$this->alias,
				'_index'=>$this->_index,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $this->config['template'])){
				//调用app的view文件
				\F::app()->view->renderPartial($this->config['template'], array(
					'posts'=>$posts,
					'config'=>$this->config,
					'alias'=>$this->alias,
					'_index'=>$this->_index,
				));
			}else{
				//直接视为代码执行
				$alias = $this->view->alias;
				$_index = $this->_index;
				eval('?>'.$this->config['template'].'<?php ');
			}
		}
	}
	
	/**
	 * 初始化配置
	 * @param array $config
	 * @return array
	 */
	private function initConfig($config){
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
		
		//仅返回有缩略图的文章
		if(empty($config['thumbnail'])){
			$config['thumbnail'] = false;
		}else{
			$config['thumbnail'] = true;
		}
		
		isset($config['subclassification']) && $config['subclassification'] = true;
		
		empty($config['fields']) && $config['fields'] = array();
		
		return $this->config = $config;
	}
	
	/**
	 * 获取排序方式
	 * @return string
	 */
	private function getOrder(){
		if(!empty($this->config['order']) && isset($this->order_map[$this->config['order']])){
			return $this->order_map[$this->config['order']];
		}else{
			return $this->order_map['hand'];
		}
	}
	
	/**
	 * 获取$fields
	 * @return array
	 */
	private function getFields(){
		$fields = array(
			'post'=>$this->fields['post']
		);
		if(!empty($this->config['post_thumbnail_width']) || !empty($this->config['post_thumbnail_height'])){
			$fields['post']['extra'] = array(
				'thumbnail'=>(empty($this->config['post_thumbnail_width']) ? 0 : $this->config['post_thumbnail_width']) .
					'x' .
					(empty($this->config['post_thumbnail_height']) ? 0 : $this->config['post_thumbnail_height']),
			);
		}
		
		if(in_array('category', $this->config['fields'])){
			$fields['category'] = $this->fields['category'];
		}
		if(in_array('meta', $this->config['fields'])){
			$fields['meta'] = $this->fields['meta'];
		}
		if(in_array('user', $this->config['fields'])){
			$fields['user'] = $this->fields['user'];
		}
		if(in_array('files', $this->config['fields'])){
			$file_fields = $this->fields['files'];
			if(!empty($this->config['file_thumbnail_width']) || !empty($this->config['file_thumbnail_height'])){
				$file_fields['extra'] = array(
					'thumbnail'=>(empty($this->config['file_thumbnail_width']) ? 0 : $this->config['file_thumbnail_width']) .
						'x' .
						(empty($this->config['file_thumbnail_height']) ? 0 : $this->config['file_thumbnail_height']),
				);
			}
			$fields['files'] = $file_fields;
		}
		
		return $fields;
	}
	
	/**
	 * 获取附加条件
	 * @return array
	 */
	private function getConditions(){
		$conditions = array();
		if($this->config['thumbnail']){
			$conditions[] = 'thumbnail != 0';
		}
		
		//限制最后访问时间，防止推出很古老的热门文章
		if(!empty($this->config['last_view_time'])){
			$conditions[] = 'last_view_time > '.(\F::app()->current_time - 86400 * $this->config['last_view_time']);
		}
		
		return $conditions;
	}
	
	/**
	 * @param array $posts
	 * @return array
	 */
	private function formatPosts($posts){
		foreach($posts as &$p){
			//附加格式化日期
			if($this->config['date_format'] == 'pretty'){
				$p['post']['format_publish_time'] = Date::niceShort($p['post']['publish_time']);
			}else if($this->config['date_format']){
				$p['post']['format_publish_time'] = \date($this->config['date_format'], $p['post']['publish_time']);
			}else{
				$p['post']['format_publish_time'] = '';
			}
			
			//附加文章链接
			$p['post']['link'] = $this->view->url(str_replace('{$id}', $p['post']['id'], $this->config['uri']));
		}
		
		return $posts;
	}
}