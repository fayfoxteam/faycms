<?php
namespace fay\widgets\menu\controllers;

use fay\core\Widget;
use fay\models\Category;
use fay\models\Menu;

class IndexController extends Widget{
	public function index($config){
		//root node
		if(empty($config['top'])){
			$root_node = Category::model()->getByAlias('_system_post', 'id');
			$config['top'] = $root_node['id'];
		}
		
		$menus = Menu::model()->getTree($config['top'], true, true);
		
		//若无分类可显示，则不显示该widget
		if(empty($menus)){
			return;
		}
		
		if(empty($config['template'])){
			$this->view->render('template', array(
				'menus'=>$menus,
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'menus'=>$menus,
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