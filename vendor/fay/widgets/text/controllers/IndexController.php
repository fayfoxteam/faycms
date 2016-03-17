<?php
namespace fay\widgets\text\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	public function getData($config){
		return $config['content'];
	}
	
	public function index($config){
		$this->view->assign(array(
			'config'=>$config,
			'alias'=>$this->alias,
			'_index'=>$this->_index,
		))->render();
	}
}