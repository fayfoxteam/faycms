<?php
namespace fay\widgets\page_item\controllers;

use fay\widget\Widget;
use fay\services\FlashService;
use fay\models\tables\PagesTable;

class AdminController extends Widget{
    public function initConfig($config){
        isset($config['id_key']) || $config['id_key'] = 'page_id';
        isset($config['alias_key']) || $config['alias_key'] = 'page_alias';
        empty($config['default_page_id']) && $config['default_page_id'] = 0;
        $config['inc_views'] = empty($config['inc_views']) ? 0 : 1;
        
        if($config['default_page_id']){
            $post = PagesTable::model()->find($config['default_page_id'], 'title');
            $this->form->setData(array(
                'page_title'=>$post['title'],
            ));
        }
        
        //设置模版
        empty($config['template']) && $config['template'] = $this->getDefaultTemplate();
        
        return $this->config = $config;
    }
    
    public function index(){
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
            array('default_page_id', 'int'),
            array('default_page_id', 'exist', array('table'=>'pages', 'field'=>'id')),
            array('inc_views', 'range', array('range'=>array('0', '1'))),
        );
    }
    
    public function labels(){
        return array(
            'default_page_id'=>'固定页面ID',
            'type'=>'显示方式',
        );
    }
    
    public function filters(){
        return array(
            'type'=>'trim',
            'id_key'=>'trim',
            'alias_key'=>'trim',
            'default_page_id'=>'intval',
            'template'=>'trim',
            'inc_views'=>'intval',
        );
    }
}