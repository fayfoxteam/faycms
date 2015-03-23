<?php
namespace fay\widgets\categories\controllers;

use fay\core\Widget;
use fay\models\Category;

class IndexController extends Widget{
	public function index($data){
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
			$data['uri'] = 'cat/{$id}';
		}
		
		if(!empty($data['hierarchical'])){
			$cats = Category::model()->getTreeByParentId($data['top']);
		}else{
			$cats = Category::model()->getAllByParentId($data['top']);
		}
		
		//若无分类可显示，则不显示该widget
		if(empty($cats)){
			return;
		}
		
		if(empty($data['template'])){
			$this->view->render('template', array(
				'cats'=>$cats,
				'data'=>$data,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $data['template'])){
				\F::app()->view->renderPartial($data['template'], array(
					'cats'=>$cats,
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