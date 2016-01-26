<?php
namespace fay\widgets\categories\controllers;

use fay\core\Widget;
use fay\models\Category;

class IndexController extends Widget{
	public function getData($config){
		//root node
		if(empty($config['top'])){
			$root_node = Category::model()->getByAlias('_system_post', 'id');
			$config['top'] = $root_node['id'];
		}
		
		//uri
		if(empty($config['uri'])){
			$config['uri'] = 'cat/{$id}';
		}
		
		if(!empty($config['hierarchical'])){
			$cats = Category::model()->getTree($config['top']);
		}else{
			$cats = Category::model()->getAll($config['top']);
		}
		
		//格式化分类链接
		$cats = $this->setLink($cats, $config['uri']);
		
		return $cats;
	}
	
	public function index($config){
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
		if(empty($config['uri'])){
			$config['uri'] = 'cat/{$id}';
		}
		
		if(!empty($config['hierarchical'])){
			$cats = Category::model()->getTree($config['top']);
		}else{
			$cats = Category::model()->getAll($config['top']);
		}
		
		//格式化分类链接
		$cats = $this->setLink($cats, $config['uri']);
		
		//若无分类可显示，则不显示该widget
		if(empty($cats)){
			return;
		}
		
		if(empty($config['template'])){
			$this->view->render('template', array(
				'cats'=>$cats,
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'cats'=>$cats,
					'config'=>$config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				eval('?>'.$config['template'].'<?php ');
			}
		}
	}
	
	/**
	 * 为分类列表添加link字段
	 * @param array $cats
	 */
	private function setLink($cats, $uri){
		foreach($cats as &$c){
			$c['link'] = $this->view->url(str_replace(array(
				'{$id}', '{$alias}',
			), array(
				$c['id'], $c['alias'],
			), $uri));
			
			if(!empty($c['children'])){
				$c['children'] = $this->setLink($c['children'], $uri);
			}
		}
		
		return $cats;
	}
}