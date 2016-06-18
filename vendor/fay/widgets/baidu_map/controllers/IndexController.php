<?php
namespace fay\widgets\baidu_map\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
	public function index($config){
		$this->view->render('index', array(
			'config'=>$config,
			'alias'=>$this->alias,
			'_index'=>$this->_index,
		));
	}
}