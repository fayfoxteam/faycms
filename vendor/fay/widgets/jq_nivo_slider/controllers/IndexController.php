<?php
namespace fay\widgets\jq_nivo_slider\controllers;

use fay\core\Widget;

class IndexController extends Widget{
	public function getData($config){
		return empty($config['files']) ? array() : $config['files'];
	}
	
	public function index($config){
		if(empty($config)){
			$config = array();
		}
		empty($config['animSpeed']) && $config['animSpeed'] = 500;
		empty($config['pauseTime']) && $config['pauseTime'] = 5000;
		empty($config['effect']) && $config['effect'] = 'random';
		empty($config['elementId']) && $config['elementId'] = 'slide';
		isset($config['directionNav']) || $config['directionNav'] = '1';
		
		$this->view->config = $config;
		$this->view->render();
	}
}