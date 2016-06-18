<?php
namespace fay\widgets\menu\controllers;

use fay\widget\Widget;
use fay\models\Menu;
use fay\models\tables\Menus;

class IndexController extends Widget{
	public function getData($config){
		if(empty($config['top'])){
			$config['top'] = Menus::ITEM_USER_MENU;
		}
		
		$menus = Menu::model()->getTree($config['top'], true, true);
		$this->removeFields($menus);
		return $menus;
	}
	
	/**
	 * 移除一些对客户端没用的字段
	 * @param array $menus
	 */
	private function removeFields(&$menus){
		foreach($menus as &$m){
			unset($m['left_value'],$m['right_value'],$m['sort']);
			if(!empty($m['children'])){
				$this->removeFields($m['children']);
			}
		}
	}
	
	public function index($config){
		//root node
		if(empty($config['top'])){
			$config['top'] = Menus::ITEM_USER_MENU;
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
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
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