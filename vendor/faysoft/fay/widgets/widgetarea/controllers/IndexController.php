<?php
namespace fay\widgets\widgetarea\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
	public function getData($config){
		
	}
	
	public function index($config){
		if(empty($config['alias'])){
			return '';
		}
		
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
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