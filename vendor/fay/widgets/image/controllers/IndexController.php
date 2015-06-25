<?php
namespace fay\widgets\image\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	
	public function index($config){
		$this->view->alias = $this->alias;
		$this->view->config = $config;
		$this->view->render();
	}
}