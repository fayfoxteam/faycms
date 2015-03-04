<?php
namespace fay\widgets\category_post\controllers;

use fay\core\Widget;
use fay\models\Category;
use fay\models\Post;

class IndexController extends Widget{
	public function index($data){
		$conditions = array();
		
		//root node
		if(empty($data['top'])){
			$root_node = Category::model()->getByAlias('_system_post', 'id');
			$data['top'] = $root_node['id'];
		}
		
		//title
		if(empty($data['title'])){
			$node = Category::model()->get($data['top'], 'title');
			$data['title'] = $node['title'];
		}
		
		//uri
		if(empty($data['uri'])){
			$data['uri'] = 'post/{$id}';
		}
		
		//number
		if(empty($data['number'])){
			$data['number'] = 5;
		}
		
		//date format
		if(empty($data['date_format'])){
			$data['date_format'] = '';
		}
		
		//thumbnail
		if(!empty($data['thumbnail'])){
			$conditions[] = 'thumbnail != 0';
		}
		
		//last view
		if(!empty($data['last_view_time'])){
			$conditions[] = 'last_view_time > '.(\F::app()->current_time - 86400 * $data['last_view_time']);
		}
		
		//order
		$orders = array(
			'hand'=>'is_top DESC, sort, publish_time DESC',
			'publish_time'=>'publish_time DESC',
			'views'=>'views DESC, publish_time DESC',
			'rand'=>'RAND()',
		);
		if(!empty($data['order']) && isset($orders[$data['order']])){
			$order = $orders[$data['order']];
		}else{
			$order = $orders['hand'];
		}
		
		if(!isset($data['subclassification'])){
			$data['subclassification'] = true;
		}
		
		$posts = Post::model()->getByCatId($data['top'], $data['number'], 'id,title,thumbnail,publish_time,abstract', $data['subclassification'], $order, $conditions);
		
		//若无文章可显示，则不显示该widget
		if(empty($posts)){
			return;
		}
		
		//template
		if(empty($data['template'])){
			$this->view->render('template', array(
				'posts'=>$posts,
				'data'=>$data,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $data['template'])){
				\F::app()->view->renderPartial($data['template'], array(
					'posts'=>$posts,
					'data'=>$data,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				eval('?>'.$data['template'].'<?php ');
			}
		}
		
	}
}