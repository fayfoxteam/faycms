<?php
namespace cms\widgets\contact\controllers;

use fay\widget\Widget;
use cms\services\FlashService;

class AdminController extends Widget{
    public function initConfig($config){
        //默认表单元素
        isset($config['elements']) || $config['elements'] = array(
            'name', 'email', 'content',
        );
        
        //默认标签
        isset($config['label']) || $config['label'] = array(
            'name' => '称呼',
            'email' => '邮箱',
            'content' => '内容',
            'mobile' => '电话',
            'title' => '标题',
            'country' => '国家',
        );
        
        //默认占位符
        isset($config['placeholder']) || $config['placeholder'] = $config['label'];
        
        //默认必填项
        isset($config['require_message']) || $config['require_message'] = array(
            'content'=>'内容不能为空'
        );
        
        //设置模版
        $this->parseTemplateForEdit($config);
        
        return $this->config = $config;
    }
    
    public function index(){
        $this->view->render();
    }
    
    public function onPost(){
        $data = $this->form->getFilteredData();
        
        if(isset($data['elements'])){
            //对表单元素进行排序
            $temp_elements = $data['elements'];
            $data['elements'] = array();
            foreach($data['label'] as $element => $label){
                if(in_array($element, $temp_elements)){
                    $data['elements'][] = $element;
                }
            }
        }else{
            $data['elements'] = array();
        }
        
        $this->saveConfig($data);
        FlashService::set('编辑成功', 'success');
    }
    
    public function rules(){
        return array(
            
        );
    }
    
    public function labels(){
        return array(
            'title'=>'标题',
            'label'=>'标签',
            'placeholder'=>'占位符',
            'elements'=>'表单元素',
            'require_message'=>'必填报错语',
            'format_message'=>'格式报错语',
            'submit_text'=>'提交按钮文案',
            'submit_btn_class'=>'提交按钮css类',
            'submit_success'=>'提交成功文案',
        );
    }
    
    public function filters(){
        return array(
            'title'=>'trim',
            'label'=>'trim',
            'placeholder'=>'trim',
            'elements'=>'trim',
            'require_message'=>'trim',
            'format_message'=>'trim',
            'submit_text'=>'trim',
            'submit_btn_class'=>'trim',
            'submit_success'=>'trim',
            'template'=>'trim',
            'template_code'=>'trim',
        );
    }
}