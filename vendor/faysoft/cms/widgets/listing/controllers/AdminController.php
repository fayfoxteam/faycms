<?php
namespace cms\widgets\listing\controllers;

use fay\widget\Widget;

class AdminController extends Widget{
    public function initConfig($config){
        //若未设置任何数据，默认一个空的
        empty($config['data']) && $config['data'] = array('');
        
        //设置模版
        $this->parseTemplateForEdit($config);
        
        return $this->config = $config;
    }
    
    public function index(){
        $this->view->render();
    }
    
    public function onPost(){
        $data = $this->form->getFilteredData();
        
        $values = $this->input->post('data', null, array());
        $data['data'] = array();
        foreach($values as $v){
            $data['data'][] = $v;
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