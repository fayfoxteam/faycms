<?php
namespace blog\modules\frontend\controllers;

use blog\library\FrontController;

class TestController extends FrontController{
	public function devicemotion(){
		$this->view->renderPartial();
	}
}