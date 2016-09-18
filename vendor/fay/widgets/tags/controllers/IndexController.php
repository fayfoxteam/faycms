<?php
namespace fay\widgets\tags\controllers;

use fay\widget\Widget;
use fay\services\Category;

class IndexController extends Widget{
	public function getData($config){
		//uri
		if(empty($config['uri'])){
			$config['uri'] = 'cat/{$id}';
		}
		
		if(!empty($config['hierarchical'])){
			$tags = Category::service()->getTree($config['top']);
		}else{
			$tags = Category::service()->getChildren($config['top']);
		}
		
		//格式化分类链接
		$tags = $this->setLink($tags, $config['uri']);
		
		return $tags;
	}
	
	public function index($config){
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
		if(empty($config['uri'])){
			$config['uri'] = 'cat/{$id}';
		}
		
		if(!empty($config['hierarchical'])){
			$tags = Category::service()->getTree($config['top']);
		}else{
			$tags = Category::service()->getChildren($config['top']);
		}
		
		//格式化分类链接
		$tags = $this->setLink($tags, $config['uri']);
		
		//若无分类可显示，则不显示该widget
		if(empty($tags)){
			return;
		}
		
		if(empty($config['template'])){
			$this->view->render('template', array(
				'cats'=>$tags,
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'cats'=>$tags,
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
	 * @param array $tags
	 * @param string $uri
	 * @return array
	 */
	private function setLink($tags, $uri){
		foreach($tags as &$c){
			$c['link'] = $this->view->url(str_replace(array(
				'{$id}', '{$alias}',
			), array(
				$c['id'], $c['alias'],
			), $uri));
			
			if(!empty($c['children'])){
				$c['children'] = $this->setLink($c['children'], $uri);
			}
		}
		
		return $tags;
	}
}