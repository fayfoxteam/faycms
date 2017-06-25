<?php
namespace cms\widgets\tag_post_list\controllers;

use cms\services\CategoryService;
use fay\widget\Widget;

class AdminController extends Widget{
    public function initConfig($config){
        //设置模版
        $this->parseTemplateForEdit($config);
        
        return $this->config = $config;
    }
    
    public function index(){
        $root_node = CategoryService::service()->getByAlias('_system_post', 'id');
        $this->view->assign(array(
            'cats'=>array(
                array(
                    'id'=>0,
                    'title'=>'不限制',
                    'children'=>CategoryService::service()->getTreeByParentId($root_node['id']),
                ),
            ),
        ))->render();
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
    }
    
    public function rules(){
        return array(
            array('page_size', 'int', array('min'=>1)),
            array(array('file_thumbnail_width', 'file_thumbnail_height', 'post_thumbnail_width', 'post_thumbnail_height'), 'int', array('min'=>0)),
            array('pager', 'range', array('range'=>array('system', 'custom'))),
            array('cat_id', 'exist', array('table'=>'categories', 'field'=>'id')),
        );
    }
    
    public function labels(){
        return array(
            'page_size'=>'分页大小',
            'page_key'=>'页码字段',
            'tag_key'=>'分类字段',
            'cat_id'=>'限定分类',
            'tag_id_key'=>'标签ID字段',
            'tag_title_key'=>'标签名称字段',
            'post_thumbnail_width'=>'文章缩略图宽度',
            'post_thumbnail_height'=>'文章缩略图高度',
            'file_thumbnail_width'=>'附件缩略图宽度',
            'file_thumbnail_height'=>'附件缩略图高度',
        );
    }
    
    public function filters(){
        return array(
            'page_size'=>'intval',
            'page_key'=>'trim',
            'order'=>'trim',
            'date_format'=>'trim',
            'template'=>'trim',
            'template_code'=>'trim',
            'fields'=>'trim',
            'pager'=>'trim',
            'pager_template'=>'trim',
            'empty_text'=>'trim',
            'cat_id'=>'intval',
            'subclassification'=>'intval',
            'tag_id_key'=>'trim',
            'tag_title_key'=>'trim',
            'post_thumbnail_width'=>'intval',
            'post_thumbnail_height'=>'intval',
            'file_thumbnail_width'=>'intval',
            'file_thumbnail_height'=>'intval',
        );
    }
}