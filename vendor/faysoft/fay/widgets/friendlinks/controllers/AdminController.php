<?php
namespace fay\widgets\friendlinks\controllers;

use fay\widget\Widget;
use fay\services\CategoryService;
use fay\services\FlashService;

class AdminController extends Widget{
    public function initConfig($config){
        //设置模版
        empty($config['template']) && $config['template'] = $this->getDefaultTemplate();
        
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
        
        //若模版与默认模版一致，不保存
        if($this->isDefaultTemplate($data['template'])){
            $data['template'] = '';
        }
        
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
        );
    }
}