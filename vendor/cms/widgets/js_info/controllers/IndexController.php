<?php
namespace cms\widgets\js_info\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	public function index($options){
		$this->view->render();
	}
}