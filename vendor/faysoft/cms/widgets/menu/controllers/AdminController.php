<?php
namespace cms\widgets\menu\controllers;

use fay\widget\Widget;
use cms\services\MenuService;
use cms\models\tables\MenusTable;
use cms\services\FlashService;

class AdminController extends Widget{
    public function initConfig($config){
        //设置模版
        $this->parseTemplateForEdit($config);
        
        return $this->config = $config;
    }
    
    public function index(){
        $this->view->menu = array(
            array(
                'id'=>MenusTable::ITEM_USER_MENU,
                'title'=>'顶级',
                'children'=>MenuService::service()->getTree(MenusTable::ITEM_USER_MENU, true, true),
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
            array(array('top'), 'int', array('min'=>0))
        );
    }
    
    public function labels(){
        return array(
            'top'=>'顶级菜单',
        );
    }
    
    public function filters(){
        return array(
            'top'=>'intval',
            'template'=>'trim',
            'template_code'=>'trim',
        );
    }
    
}