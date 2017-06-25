<?php
namespace cms\widgets\options\controllers;

use fay\widget\Widget;

class AdminController extends Widget{
    public function initConfig($config){
        empty($config['title']) && $config['title'] = '';
        empty($config['data']) && $config['data'] = array();
        
        //设置模版
        $this->parseTemplateForEdit($config);
        
        return $this->config = $config;
    }
    
    public function index(){
        $this->view->render();
    }
    
    public function onPost(){
        $data = $this->form->getFilteredData();
        $keys = $this->input->post('keys', null, array());
        $values = $this->input->post('values', null, array());
        
        $data['data'] = array();
        foreach($keys as $i=>$k){
            $data['data'][] = array(
                'key'=>$k,
                'value'=>isset($values[$i]) ? $values[$i] : '',
            );
        }
        
        if($this->isDefaultTemplate($data['template'])){
            $data['template'] = '';
        }
        
        $this->saveConfig($data);
    }
    
    public function rules(){
        return array();
    }
    
    public function labels(){
        return array(
            'title'=>'标题',
            'template'=>'模版',
        );
    }
    
    public function filters(){
        return array(
            'title'=>'trim',
            'template'=>'trim',
            'template_code'=>'trim',
        );
    }
}