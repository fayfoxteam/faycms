<?php
namespace cms\widgets\jq_camera\controllers;

use cms\services\file\FileService;
use fay\widget\Widget;

class IndexController extends Widget{
    public function initConfig($config){
        empty($config['files']) && $config['files'] = array();
        isset($config['element_id']) || $config['element_id'] = '';
        isset($config['height']) || $config['height'] = 450;
        isset($config['transPeriod']) || $config['transPeriod'] = 800;
        isset($config['time']) || $config['time'] = 5000;
        isset($config['fx']) || $config['fx'] = 'random';
        
        return $this->config = $config;
    }
    
    public function getData(){
        $files = $this->config['files'];
        
        foreach($files as $k => $f){
            if((!empty($f['start_time']) && \F::app()->current_time < $f['start_time']) ||
                (!empty($f['end_time']) && \F::app()->current_time > $f['end_time'])){
                unset($files[$k]);
                continue;
            }
            
            $files[$k]['src'] = FileService::getUrl($f['file_id']);
        }
        
        $config = $this->config;
        $config['files'] = $files;
        return $config;
    }
    
    public function index(){
        $data = $this->getData();
        
        $this->renderTemplate(array(
            'files'=>$data['files'],
        ));
    }
}