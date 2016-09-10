<?php
namespace church\modules\frontend\controllers;

use church\library\FrontController;

class TagController extends FrontController{
	public function item(){
		dump($this->input->get('tag'));die;
		
		$this->view->render();
	}
}