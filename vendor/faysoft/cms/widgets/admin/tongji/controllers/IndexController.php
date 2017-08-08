<?php
namespace cms\widgets\admin\tongji\controllers;

use cms\services\AnalystService;
use fay\widget\Widget;

class IndexController extends Widget{
    public function index(){
        $this->view->today = array(
            'ip' => AnalystService::service()->getIP(),
            'pv' => AnalystService::service()->getPV(),
            'uv' => AnalystService::service()->getUV(),
            'new_visitors' => AnalystService::service()->getNewVisitors(),
            'bounce_rate'=>AnalystService::service()->getBounceRate(),
        );
        $yesterday = date('Y-m-d', strtotime('yesterday'));
        
        if(!$yesterday_analyst = AnalystService::service()->getCache($yesterday)){
            $yesterday_analyst = AnalystService::service()->setCache($yesterday);
        }
        $this->view->yesterday = $yesterday_analyst;
        
        return $this->view->render();
    }
    
    public function placeholder(){
        
        return $this->view->render('placeholder');
    }
}