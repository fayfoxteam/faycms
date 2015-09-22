<?php
namespace fay\widgets\images\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	public function getData($config){
		return empty($config['files']) ? array() : $config['files'];
	}
	
	public function index($config){
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'files'=>isset($config['files']) ? $config['files'] : array(),
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'files'=>isset($config['files']) ? $config['files'] : array(),
					'config'=>$config,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				$files = isset($config['files']) ? $config['files'] : array();
				eval('?>'.$config['template'].'<?php ');
			}
		}
	}
}