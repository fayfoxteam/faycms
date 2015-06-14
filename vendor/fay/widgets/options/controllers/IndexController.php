<?php
namespace fay\widgets\options\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	
	public function index($config){
		$this->view->config = $config;
		$this->view->render();
	}
}