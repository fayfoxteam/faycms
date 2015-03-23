<?php
namespace fay\widgets\menu\controllers;

use fay\core\Widget;
use fay\models\Category;
use fay\models\Menu;

class IndexController extends Widget{
	public function index($data){
		//root node
		if(empty($data['top'])){
			$root_node = Category::model()->getByAlias('_system_post', 'id');
			$data['top'] = $root_node['id'];
		}
		
		$menu = Menu::model()->getTree($data['top'], true, true);
		
		//若无分类可显示，则不显示该widget
		if(empty($menu)){
			return;
		}
		
		if(empty($data['template'])){
			$this->view->render('template', array(
				'menu'=>$menu,
				'data'=>$data,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $data['template'])){
				\F::app()->view->renderPartial($data['template'], array(
					'menu'=>$menu,
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