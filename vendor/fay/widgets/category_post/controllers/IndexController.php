<?php
namespace fay\widgets\category_post\controllers;

use fay\core\Widget;
use fay\models\Category;
use fay\models\Post;
use fay\helpers\Date;

class IndexController extends Widget{
	public function index($config){
		$conditions = array();
		
		//root node
		if(empty($config['top'])){
			$root_node = Category::model()->getByAlias('_system_post', 'id');
			$config['top'] = $root_node['id'];
		}
		
		//title
		if(empty($config['title'])){
			$node = Category::model()->get($config['top'], 'title');
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
		
		$posts = Post::model()->getByCatId($config['top'], $config['number'], 'id,title,user_id,thumbnail,publish_time,abstract', $config['subclassification'], $order, $conditions);
		
		//若无文章可显示，则不显示该widget
		if(empty($posts) && !$config['show_empty']){
			return;
		}
		
		foreach($posts as &$p){
			if($config['date_format'] == 'pretty'){
				$p['format_publish_time'] = Date::niceShort($p['publish_time']);
			}else if($config['date_format']){
				$p['format_publish_time'] = \date($config['date_format'], $p['publish_time']);
			}else{
				$p['format_publish_time'] = '';
			}
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
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $config['template'])){
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