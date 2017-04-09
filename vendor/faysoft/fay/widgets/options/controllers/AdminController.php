<?php
namespace fay\widgets\options\controllers;

use fay\widget\Widget;
use fay\services\FlashService;

class AdminController extends Widget{
    public function initConfig($config){
        empty($config['title']) && $config['title'] = '';
        empty($config['data']) && $config['data'] = array();
        
        //设置模版
        empty($config['template']) && $config['template'] = $this->getDefaultTemplate();
        
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
        FlashService::set('编辑成功', 'success');
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
        );
    }
}