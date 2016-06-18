<?php
namespace cms\widgets\tongji\controllers;

use fay\widget\Widget;
use fay\models\Analyst;

class IndexController extends Widget{
	public function index($options){
		$this->view->today = array(
			'ip' => Analyst::model()->getIP(),
			'pv' => Analyst::model()->getPV(),
			'uv' => Analyst::model()->getUV(),
			'new_visitors' => Analyst::model()->getNewVisitors(),
			'bounce_rate'=>Analyst::model()->getBounceRate(),
		);
		$yesterday = date('Y-m-d', strtotime('yesterday'));
		
		if(!$yesterday_analyst = Analyst::model()->getCache($yesterday)){
			$yesterday_analyst = Analyst::model()->setCache($yesterday);
		}
		$this->view->yesterday = $yesterday_analyst;
		
		$this->view->render();
	}
	
	public function placeholder(){
		
		$this->view->render('placeholder');
	}
}