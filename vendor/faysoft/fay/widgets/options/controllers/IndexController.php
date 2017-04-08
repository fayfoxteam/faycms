<?php
namespace fay\widgets\options\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
	public function initConfig($config){
		empty($config['title']) && $config['title'] = '';
		empty($config['data']) && $config['data'] = array();
		
		return $this->config = $config;
	}
	
	public function getData(){
		return array(
			'title'=>$this->config['title'],
			'data'=>$this->config['data'],
		);
	}
	
	public function index(){
		$this->renderTemplate($this->getData());
	}
}