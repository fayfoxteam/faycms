<?php
namespace blog\widgets\profile\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	
	public function index($options){
		$this->view->render();
	}
	
}