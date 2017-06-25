<?php
namespace cms\widgets\image\controllers;

use fay\widget\Widget;

class AdminController extends Widget{
    public function initConfig($config){
        isset($config['file_id']) || $config['file_id'] = 0;
        
        //设置模版
        $this->parseTemplateForEdit($config);
        
        return $this->config = $config;
    }
    
    public function index(){
        $this->view->render();
    }
    
    /**
     * 当有post提交的时候，会自动调用此方法
     */
    public function onPost(){
        $data = $this->form->getFilteredData();

        $this->saveConfig($data);
    }
    
    public function rules(){
        return array(
            array(array('file_id', 'width', 'height'), 'int'),
            array('link', 'url'),
        );
    }
    
    public function labels(){
        return array(
            'width'=>'宽',
            'height'=>'高',
            'link'=>'链接地址',
        );
    }
    
    public function filters(){
        return array(
            'file_id'=>'intval',
            'width'=>'intval',
            'height'=>'intval',
            'link'=>'trim',
            'target'=>'trim',
            'template'=>'trim',
            'template_code'=>'trim',
        );
    }
}