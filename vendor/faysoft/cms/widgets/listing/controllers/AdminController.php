<?php
namespace cms\widgets\listing\controllers;

use fay\widget\Widget;
use cms\services\FlashService;

class AdminController extends Widget{
    public function initConfig($config){
        //若未设置任何数据，默认一个空的
        empty($config['data']) && $config['data'] = array('');
        
        //设置模版
        empty($config['template']) && $config['template'] = $this->getDefaultTemplate();
        
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
        
        //若模版与默认模版一致，不保存
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