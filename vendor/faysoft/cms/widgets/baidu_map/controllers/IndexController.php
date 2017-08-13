<?php
namespace cms\widgets\baidu_map\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
    public function index(){
        if(empty($this->config['ak'])){
            throw new \ErrorException('百度地图小工具未配置');
        }
        
        return $this->view->render();
    }
}