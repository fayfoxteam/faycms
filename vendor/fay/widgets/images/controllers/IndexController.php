<?php
namespace fay\widgets\images\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	
	public function index($data){
		//template
		if(empty($data['template'])){
			$this->view->render('template', array(
				'files'=>isset($data['files']) ? $data['files'] : array(),
				'data'=>$data,
				'alias'=>$this->alias,
			));
		}else{
			if(preg_match('/^[\w_-]+\/[\w_-]+\/[\w_-]+$/', $data['template'])){
				\F::app()->view->renderPartial($data['template'], array(
					'files'=>isset($data['files']) ? $data['files'] : array(),
					'data'=>$data,
					'alias'=>$this->alias,
				));
			}else{
				$alias = $this->alias;
				$files = isset($data['files']) ? $data['files'] : array();
				eval('?>'.$data['template'].'<?php ');
			}
		}
	}
}