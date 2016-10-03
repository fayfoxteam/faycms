<?php
namespace cms\widgets\tongji\controllers;

use fay\widget\Widget;
use fay\services\Analyst;

class IndexController extends Widget{
	public function index($options){
		$this->view->today = array(
			'ip' => Analyst::service()->getIP(),
			'pv' => Analyst::service()->getPV(),
			'uv' => Analyst::service()->getUV(),
			'new_visitors' => Analyst::service()->getNewVisitors(),
			'bounce_rate'=>Analyst::service()->getBounceRate(),
		);
		$yesterday = date('Y-m-d', strtotime('yesterday'));
		
		if(!$yesterday_analyst = Analyst::service()->getCache($yesterday)){
			$yesterday_analyst = Analyst::service()->setCache($yesterday);
		}
		$this->view->yesterday = $yesterday_analyst;
		
		$this->view->render();
	}
	
	public function placeholder(){
		
		$this->view->render('placeholder');
	}
}