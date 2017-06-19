<?php
namespace cms\widgets\admin\tongji_chart\controllers;

use cms\services\AnalystService;
use fay\core\Response;
use fay\widget\Widget;

class IndexController extends Widget{
    public function init(){
        $this->now_hour = date('G');
        $this->now_date = date('Y-m-d');
        $this->yesterday_date = date('Y-m-d', strtotime('yesterday'));
    }
    
    public function index(){
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
        $today_cache = AnalystService::service()->getHourCacheByDay($this->now_date);
        for($i = 0; $i < $this->now_hour; $i++){
            //当日非当时，设置缓存
            if(isset($today_cache[$i])){
                $today[] = intval($today_cache[$i][$type]);
            }else{
                $data = AnalystService::service()->setCache($this->now_date, $i);
                $today[] = intval($data[$type]);
            }
        }
        
        //当前时间，当日总量 实时获取
        if($type == 'pv'){
            $today[$i] = intval(AnalystService::service()->getPV($this->now_date, $this->now_hour));
            $today_total = intval(AnalystService::service()->getPV($this->now_date));
        }else if($type == 'uv'){
            $today[$i] = intval(AnalystService::service()->getUV($this->now_date, $this->now_hour));
            $today_total = intval(AnalystService::service()->getUV($this->now_date));
        }else if($type == 'ip'){
            $today[$i] = intval(AnalystService::service()->getIP($this->now_date, $this->now_hour));
            $today_total = intval(AnalystService::service()->getIP($this->now_date));
        }else if($type == 'new_visitors'){
            $today[$i] = intval(AnalystService::service()->getNewVisitors($this->now_date, $this->now_hour));
            $today_total = intval(AnalystService::service()->getNewVisitors($this->now_date));
        }
        
        //未到的时间默认为0
        for($i = $this->now_hour + 1; $i < 24; $i++){
            $today[] = 0;
        }
        
        $yesterday = array();
        $yesterday_cache = AnalystService::service()->getHourCacheByDay($this->yesterday_date);
        for($i = 0; $i < 24; $i++){
            if(isset($yesterday_cache[$i])){
                //直接读取缓存数据
                $yesterday[] = intval($yesterday_cache[$i][$type]);
            }else{
                //无缓存，设置缓存
                $data = AnalystService::service()->setCache($this->yesterday_date, $i);
                $yesterday[] = intval($data[$type]);
            }
        }
        
        $yesterday_total_cache = AnalystService::service()->getCache($this->yesterday_date);
        $yesterday_total_cache || $yesterday_total_cache = AnalystService::service()->setCache($this->yesterday_date);
        
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