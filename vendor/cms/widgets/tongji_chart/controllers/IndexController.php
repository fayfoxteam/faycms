<?php
namespace cms\widgets\tongji_chart\controllers;

use fay\core\Widget;
use fay\models\Analyst;
use fay\core\Response;

class IndexController extends Widget{
	public function init(){
		$this->now_hour = date('G');
		$this->now_date = date('Y-m-d');
		$this->yesterday_date = date('Y-m-d', strtotime('yesterday'));
	}
	
	public function index($options){
		$analyst = $this->getAnalyst('pv');
		
		$this->view->today = $analyst['today'];
		$this->view->yesterday = $analyst['yesterday'];
		$this->view->today_total = $analyst['today_total'];
		$this->view->yesterday_total = $analyst['yesterday_total'];
		
 		$this->view->render();
	}
	
	public function placeholder(){
		
		$this->view->render('placeholder');
	}
	
	private function getAnalyst($type){
		$today = array();
		$today_cache = Analyst::model()->getHourCacheByDay($this->now_date);
		for($i = 0; $i < $this->now_hour; $i++){
			//当日非当时，设置缓存
			if(isset($today_cache[$i])){
				$today[] = intval($today_cache[$i][$type]);
			}else{
				$data = Analyst::model()->setCache($this->now_date, $i);
				$today[] = intval($data[$type]);
			}
		}
		
		//当前时间，当日总量 实时获取
		if($type == 'pv'){
			$today[$i] = intval(Analyst::model()->getPV($this->now_date, $this->now_hour));
			$today_total = intval(Analyst::model()->getPV($this->now_date));
		}else if($type == 'uv'){
			$today[$i] = intval(Analyst::model()->getUV($this->now_date, $this->now_hour));
			$today_total = intval(Analyst::model()->getUV($this->now_date));
		}else if($type == 'ip'){
			$today[$i] = intval(Analyst::model()->getIP($this->now_date, $this->now_hour));
			$today_total = intval(Analyst::model()->getIP($this->now_date));
		}else if($type == 'new_visitors'){
			$today[$i] = intval(Analyst::model()->getNewVisitors($this->now_date, $this->now_hour));
			$today_total = intval(Analyst::model()->getNewVisitors($this->now_date));
		}
		
		//未到的时间默认为0
		for($i = $this->now_hour + 1; $i < 24; $i++){
			$today[] = 0;
		}
		
		$yesterday = array();
		$yesterday_cache = Analyst::model()->getHourCacheByDay($this->yesterday_date);
		for($i = 0; $i < 24; $i++){
			if(isset($yesterday_cache[$i])){
				//直接读取缓存数据
				$yesterday[] = intval($yesterday_cache[$i][$type]);
			}else{
				//无缓存，设置缓存
				$data = Analyst::model()->setCache($this->yesterday_date, $i);
				$yesterday[] = intval($data[$type]);
			}
		}
		
		$yesterday_total_cache = Analyst::model()->getCache($this->yesterday_date);
		$yesterday_total_cache || $yesterday_total_cache = Analyst::model()->setCache($this->yesterday_date);
		
		return array(
			'today'=>$today,
			'today_total'=>$today_total,
			'yesterday'=>$yesterday,
			'yesterday_total'=>intval($yesterday_total_cache[$type]),
		);
	}
	
	public function getData(){
		$type = $this->input->get('t');
		if(!in_array($type, array('pv', 'uv', 'ip', 'new_visitors'))){
			Response::json('', 0, '参数异常');
		}
		$analyst = $this->getAnalyst($type);
		Response::json(array(
			'today'=>$analyst['today'],
			'yesterday'=>$analyst['yesterday'],
			'today_total'=>$analyst['today_total'],
			'yesterday_total'=>$analyst['yesterday_total'],
		));
	}
}