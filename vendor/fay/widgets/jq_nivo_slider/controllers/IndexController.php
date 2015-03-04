<?php
namespace fay\widgets\jq_nivo_slider\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	
	public function index($data){
		if(empty($data)){
			$data = array();
		}
		empty($data['animSpeed']) && $data['animSpeed'] = 500;
		empty($data['pauseTime']) && $data['pauseTime'] = 5000;
		empty($data['effect']) && $data['effect'] = 'random';
		empty($data['elementId']) && $data['elementId'] = 'slide';
		isset($data['directionNav']) || $data['directionNav'] = '1';
		
		$this->view->data = $data;
		$this->view->render();
	}
}