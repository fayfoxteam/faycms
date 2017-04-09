<?php
namespace cms\widgets\baidu_map\controllers;

use fay\core\ErrorException;
use fay\widget\Widget;

class IndexController extends Widget{
    public function index(){
        if(empty($this->config['ak'])){
            throw new ErrorException('百度地图小工具未配置');
        }
        
        $this->view->render();
    }
}