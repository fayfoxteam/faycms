<?php
namespace fay\widgets\listing\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	
	public function index($options){
		$this->view->data = $options;
		$this->view->render();
	}
}