<?php
namespace cms\widgets\images\controllers;

use cms\services\file\FileService;
use fay\widget\Widget;

class IndexController extends Widget{
    public function initConfig($config)
    {
        empty($config['files']) && $config['files'] = array();
        
        $config['random'] = empty($config['random']) ? 0 : 1;
        
        $config['limit'] = empty($config['limit']) ? 0 : $config['limit'];
        
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
            
            $files[$k]['src'] = FileService::getUrl($f['file_id'], (empty($this->config['width']) && empty($this->config['height'])) ? FileService::PIC_ORIGINAL : FileService::PIC_RESIZE, array(
                'dw'=>empty($this->config['width']) ? false : $this->config['width'],
                'dh'=>empty($this->config['height']) ?  false : $this->config['height'],
            ));
        }
        
        $files = array_values($files);
        
        if($this->config['random']){
            shuffle($files);
        }
        
        if($this->config['limit']){
            $files = array_slice($files, 0, $this->config['limit']);
        }
        
        return array(
            'title'=>empty($this->config['title']) ? '' : $this->config['title'],
            'files'=>$files
        );
    }
    
    public function index(){
        $this->renderTemplate($this->getData());
    }
}