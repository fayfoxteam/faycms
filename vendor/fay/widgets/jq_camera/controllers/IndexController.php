<?php
namespace fay\widgets\jq_camera\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	
	public function index($config){
		if(empty($config)){
			$config = array();
		}
		empty($config['height']) && $config['height'] = 450;
		empty($config['transPeriod']) && $config['transPeriod'] = 800;
		empty($config['time']) && $config['time'] = 5000;
		empty($config['fx']) && $config['fx'] = 'random';
		
		$this->view->config = $config;
		$this->view->render();
	}
}