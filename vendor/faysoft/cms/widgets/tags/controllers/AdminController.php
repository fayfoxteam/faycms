<?php
namespace cms\widgets\tags\controllers;

use fay\widget\Widget;

class AdminController extends Widget{
    public function initConfig($config){
        empty($config['number']) && $config['number'] = 10;
        empty($config['uri']) && $config['uri'] = 'tag/{$title}';
        empty($config['title']) && $config['title'] = '';
        
        //设置模版
        $this->parseTemplateForEdit($config);
        
        return $this->config = $config;
    }
    
    public function index(){
        return $this->view->render();
    }
    
    /**
     * 当有post提交的时候，会自动调用此方法
     */
    public function onPost(){
        $data = $this->form->getFilteredData();
        $data['uri'] || $data['uri'] = $this->input->post('other_uri');
        
        $this->saveConfig($data);
    }
    
    public function rules(){
        return array(
            array(array('number'), 'int', array('min'=>1))
        );
    }
    
    public function labels(){
        return array(
            'title'=>'标题',
            'number'=>'数量',
            'uri'=>'链接格式',
            'order'=>'排序方式',
        );
    }
    
    public function filters(){
        return array(
            'title'=>'trim',
            'number'=>'intval',
            'uri'=>'trim',
            'template'=>'trim',
            'template_code'=>'trim',
            'order'=>'trim',
        );
    }
}