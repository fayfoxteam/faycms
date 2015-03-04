<?php
namespace fay\widgets\image\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	
	public function index($data){
		$this->view->data = $data;
		$this->view->render();
	}
}