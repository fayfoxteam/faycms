<?php
namespace fay\widgets\jq_camera\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	
	public function index($data){
		if(empty($data)){
			$data = array();
		}
		empty($data['height']) && $data['height'] = 450;
		empty($data['transPeriod']) && $data['transPeriod'] = 800;
		empty($data['time']) && $data['time'] = 5000;
		empty($data['fx']) && $data['fx'] = 'random';
		
		$this->view->data = $data;
		$this->view->render();
	}
}