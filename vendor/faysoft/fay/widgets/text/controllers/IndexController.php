<?php
namespace fay\widgets\text\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
	public function initConfig($config){
		empty($config['content']) && $config['content'] = '';
		
		return $this->config = $config;
	}
	
	public function getData(){
		return $this->config['content'];
	}
	
	public function index(){
		$this->renderTemplate();
	}
}