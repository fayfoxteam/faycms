<?php
namespace cms\widgets\text\controllers;

use cms\services\FlashService;
use fay\widget\Widget;

class AdminController extends Widget{
    public function initConfig($config){
        //设置模版
        $this->parseTemplateForEdit($config);
        
        return $this->config = $config;
    }
    
    public function index(){
        $this->view->render();
    }
    
    public function onPost(){
        $data = $this->form->getFilteredData();
        
        $this->saveConfig($data);
        FlashService::set('编辑成功', 'success');
    }
    
    public function labels(){
        return array(
            'content'=>'文本',
        );
    }
    
    public function filters(){
        return array(
            'content'=>'',
            'template'=>'trim',
            'template_code'=>'trim',
        );
    }
}