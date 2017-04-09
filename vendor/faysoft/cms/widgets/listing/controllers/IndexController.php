<?php
namespace cms\widgets\listing\controllers;

use fay\widget\Widget;

class IndexController extends Widget{
    public function initConfig($config){
        //列表数据
        empty($config['data']) && $config['data'] = array();
        
        //标题
        empty($config['title']) && $config['title'] = '';
        
        return $this->config = $config;
    }
    
    public function getData(){
        return array(
            'title'=>$this->config['title'],
            'data'=>$this->config['data'],
        );
    }
    
    public function index(){
        $this->renderTemplate();
    }
}