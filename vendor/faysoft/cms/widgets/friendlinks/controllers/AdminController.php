<?php
namespace cms\widgets\friendlinks\controllers;

use fay\widget\Widget;
use cms\services\CategoryService;
use cms\services\FlashService;

class AdminController extends Widget{
    public function initConfig($config){
        //设置模版
        $this->parseTemplateForEdit($config);
        
        return $this->config = $config;
    }
    
    public function index(){
        $root_node = CategoryService::service()->getByAlias('_system_link', 'id');
        $this->view->cats = array(
            array(
                'id'=>0,
                'title'=>'不限制分类',
                'children'=>CategoryService::service()->getTreeByParentId($root_node['id']),
            ),
        );
        
        $this->view->render();
    }
    
    /**
     * 当有post提交的时候，会自动调用此方法
     */
    public function onPost(){
        $data = $this->form->getFilteredData();
        
        $this->saveConfig($data);
        FlashService::set('编辑成功', 'success');
    }
    
    public function rules(){
        return array(
            array('number', 'int', array('min'=>1, 'max'=>50)),
        );
    }
    
    public function labels(){
        return array(
            'number'=>'显示链接数',
        );
    }
    
    public function filters(){
        return array(
            'title'=>'',
            'number'=>'intval',
            'cat_id'=>'intval',
            'template'=>'trim',
            'template_code'=>'trim',
        );
    }
}