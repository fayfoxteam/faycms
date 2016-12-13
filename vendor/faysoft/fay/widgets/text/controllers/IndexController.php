<?php
namespace fay\widgets\text\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
	public function getData(){
		return $this->config['content'];
	}
	
	public function index(){
		$this->view->render();
	}
}