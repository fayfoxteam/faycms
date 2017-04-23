<?php
namespace cms\widgets\widgetarea\controllers;

use fay\helpers\ArrayHelper;
use fay\widget\Widget;
use cms\services\FlashService;

class AdminController extends Widget{
    public function initConfig($config){
        //设置模版
        $this->parseTemplateForEdit($config);
        
        return $this->config = $config;
    }
    
    public function index(){
        $this->view->assign(array(
            'widgetareas'=>ArrayHelper::column(\F::config()->getFile('widgetareas'), 'description', 'alias'),
        ))->render();
    }
    
    public function onPost(){
        $data = $this->form->getFilteredData();
        
        $this->saveConfig($data);
        FlashService::set('编辑成功', 'success');
    }
    
    public function rules(){
        return array(
            array(array('alias'), 'required'),
        );
    }
    
    public function labels(){
        return array(
            'alias'=>'小工具域',
        );
    }
    
    public function filters(){
        return array(
            'alias'=>'trim',
            'template'=>'trim',
            'template_code'=>'trim',
        );
    }
}