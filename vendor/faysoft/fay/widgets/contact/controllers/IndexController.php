<?php
namespace fay\widgets\contact\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
	public function getData($config){
		
	}
	
	public function index($config){
		$elements = array();
		if(!empty($config['elements'])){
			foreach($config['elements'] as $element){
				$elements[] = array(
					'name'=>$element,
					'label'=>isset($config['labels'][$element]) ? $config['labels'][$element] : '',
					'placeholder'=>isset($config['placeholder'][$element]) ? $config['placeholder'][$element] : '',
				);
			}
		}
		
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'config'=>$config,
				'alias'=>$this->alias,
				'elements'=>$elements,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'config'=>$config,
					'alias'=>$this->alias,
					'elements'=>$elements,
				));
			}else{
				$alias = $this->alias;
				eval('?>'.$config['template'].'<?php ');
			}
		}
	}
}