<?php
namespace fay\widgets\listing\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	
	public function index($config){
		$this->view->config = $config;
		$this->view->render();
	}
}