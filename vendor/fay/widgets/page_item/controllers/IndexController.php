<?php
namespace fay\widgets\page_item\controllers;

use fay\core\Widget;
use fay\models\Page;

class IndexController extends Widget{
	public function index($config){
		isset($config['fields']) || $config['fields'] = array('user', 'nav');
		empty($config['type']) && $config['type'] = 'by_input';
		
		if($config['type'] == 'by_input'){
			$page = Page::model()->get($this->input->get($config['id_key']));
		}else{
			$page = Page::model()->get($config['fixed_id']);
		}
		
		//template
		if(empty($config['template'])){
			$this->view->render('template', array(
				'page'=>$page,
				'config'=>$config,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $config['template'])){
				\F::app()->view->renderPartial($config['template'], array(
					'page'=>$page,
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