<?php
namespace fay\widgets\category_pages\controllers;

use fay\core\Widget;
use fay\models\Category;
use fay\core\Sql;

class IndexController extends Widget{
	public function getData($config){
		//root node
		if(empty($config['top'])){
			$root_node = Category::model()->getByAlias('_system_page', 'id');
			$config['top'] = $root_node['id'];
		}
		
		//number
		empty($config['number']) && $config['number'] = 5;
		
		$sql = new Sql();
		$pages = $sql->from('pages_categories', 'pc', '')
			->joinLeft('pages', 'p', 'pc.page_id = p.id', 'id,title,alias,thumbnail,abstract')
			->where(array('pc.cat_id = ?'=>$$config['top']))
			->fetchAll();
		
		foreach($pages as &$p){
			$p['link'] = $this->view->url(str_replace(array(
				'{$id}', '{$alias}',
			), array(
				$p['id'], $p['alias'],
			), $config['uri']));
		}
		
		return $pages;
	}
	
	public function index($config){
		//root node
		if(empty($config['top'])){
			$root_node = Category::model()->getByAlias('_system_page', 'id');
			$config['top'] = $root_node['id'];
		}
		
		//title
		if(empty($config['title'])){
			$node = Category::model()->get($config['top'], 'title');
			$config['title'] = $node['title'];
		}
		
		//number
		empty($config['number']) && $config['number'] = 5;
		
		//show_empty
		isset($config['show_empty']) || $config['show_empty'] = 0;
		
		$sql = new Sql();
		$pages = $sql->from('pages_categories', 'pc', '')
			->joinLeft('pages', 'p', 'pc.page_id = p.id', 'id,title,alias,thumbnail,abstract')
			->where(array('pc.cat_id = ?'=>$config['top']))
			->order('sort, id DESC')
			->fetchAll();
		
		//若无文章可显示，则不显示该widget
		if(empty($pages) && !$config['show_empty']){
			return;
		}
		
		foreach($pages as &$p){
			$p['link'] = $this->view->url(str_replace(array(
				'{$id}', '{$alias}',
			), array(
				$p['id'], $p['alias'],
			), $config['uri']));
		}
		
		//template
		if(empty($config['template'])){
			//调用默认模版
			$this->view->render('template', array(
				'pages'=>$pages,
				'config'=>$config,
				'alias'=>$this->alias,
				'_index'=>$this->_index,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
				//调用app的view文件
				\F::app()->view->renderPartial($config['template'], array(
					'pages'=>$pages,
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