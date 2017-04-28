<?php
namespace cms\widgets\select_posts\controllers;

use fay\widget\Widget;
use cms\services\FlashService;

class AdminController extends Widget{
    public function initConfig($config){
        //设置模版
        $this->parseTemplateForEdit($config);

        //确保posts为数组
        empty($config['posts']) && $config['posts'] = array();
        
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
        
        if(empty($data['fields'])){
            $data['fields'] = array();
        }
        $this->saveConfig($data);
        
        FlashService::set('编辑成功', 'success');
    }
    
    public function rules(){
        return array(
            array(array('file_thumbnail_width', 'file_thumbnail_height', 'post_thumbnail_width', 'post_thumbnail_height'), 'int', array('min'=>0)),
        );
    }
    
    public function labels(){
        return array(
            'post_thumbnail_width'=>'文章缩略图宽度',
            'post_thumbnail_height'=>'文章缩略图高度',
            'file_thumbnail_width'=>'附件缩略图宽度',
            'file_thumbnail_height'=>'附件缩略图高度',
        );
    }
    
    public function filters(){
        return array(
            'date_format'=>'trim',
            'template'=>'trim',
            'template_code'=>'trim',
            'fields'=>'trim',
            'post_thumbnail_width'=>'intval',
            'post_thumbnail_height'=>'intval',
            'file_thumbnail_width'=>'intval',
            'file_thumbnail_height'=>'intval',
            'posts'=>'intval',
        );
    }
}