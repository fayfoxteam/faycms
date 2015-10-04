<?php
namespace fay\widgets\listing\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	public function getData($config){
		if(isset($config['data']) && is_array($config['data'])){
			return $config['data'];
		}else{
			return array();
		}
	}
	
	public function index($config){
		//template
		if(empty($config['template'])){
			//调用默认模版
			$this->view->render('template', array(
				'values'=>(isset($config['data']) && is_array($config['data']) ? $config['data'] : array()),
				'alias'=>$this->alias,
				'_index'=>$this->_index,
			));
		}else{
			if(preg_match('/^[\w_-]+(\/[\w_-]+)+$/', $config['template'])){
				//调用app的view文件
				\F::app()->view->renderPartial($config['template'], array(
					'values'=>(isset($config['data']) && is_array($config['data']) ? $config['data'] : array()),
					'alias'=>$this->alias,
					'_index'=>$this->_index,
				));
			}else{
				//直接视为代码执行
				$alias = $this->view->alias;
				$_index = $this->_index;
				$data = (isset($config['data']) && is_array($config['data']) ? $config['data'] : array());
				eval('?>'.$config['template'].'<?php ');
			}
		}
	}
}