<?php
namespace fay\widgets\post_item\controllers;

use fay\widget\Widget;
use fay\services\FlashService;
use fay\models\tables\PostsTable;
use fay\services\CategoryService;

class AdminController extends Widget{
    public function initConfig($config){
        isset($config['id_key']) || $config['id_key'] = 'id';
        empty($config['default_post_id']) && $config['default_post_id'] = '';
        $config['inc_views'] = empty($config['inc_views']) ? 0 : 1;
        empty($config['fields']) && $config['fields'] = array();
        
        if($config['default_post_id']){
            $post = PostsTable::model()->find($config['default_post_id'], 'title');
            $this->form->setData(array(
                'fixed_title'=>$post['title'],
            ));
        }
        
        //设置模版
        empty($config['template']) && $config['template'] = $this->getDefaultTemplate();
        
        return $this->config = $config;
    }
    
    public function index(){
        //所有分类
        $root_node = CategoryService::service()->getByAlias('_system_post', 'id');
        $this->view->cats = array(
            array(
                'id'=>$root_node['id'],
                'title'=>'顶级',
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
        
        //若输入框被清空，则把ID也清空
        if(\F::input()->post('fixed_title') == ''){
            $this->form->setData(array(
                'default_post_id'=>0,
            ), true);
            $data['default_post_id'] = 0;
        }
        
        $this->saveConfig($data);
        
        FlashService::set('编辑成功', 'success');
    }
    
    public function rules(){
        return array(
            array(array('default_post_id', 'under_cat_id'), 'int', array('min'=>1)),
            array(array('file_thumbnail_width', 'file_thumbnail_height', 'post_thumbnail_width', 'post_thumbnail_height'), 'int', array('min'=>0)),
            array('inc_views', 'range', array('range'=>array('0', '1'))),
            array('under_cat_id', 'exist', array('table'=>'categories', 'field'=>'id')),
            array('default_post_id', 'exist', array('table'=>'posts', 'field'=>'id')),
        );
    }
    
    public function labels(){
        return array(
            'default_post_id'=>'默认文章',
            'under_cat_id'=>'所属分类',
            'post_thumbnail_width'=>'文章缩略图宽度',
            'post_thumbnail_height'=>'文章缩略图高度',
            'file_thumbnail_width'=>'附件缩略图宽度',
            'file_thumbnail_height'=>'附件缩略图高度',
        );
    }
    
    public function filters(){
        return array(
            'id_key'=>'trim',
            'default_post_id'=>'intval',
            'template'=>'trim',
            'fields'=>'trim',
            'under_cat_id'=>'intval',
            'inc_views'=>'intval',
            'post_thumbnail_width'=>'intval',
            'post_thumbnail_height'=>'intval',
            'file_thumbnail_width'=>'intval',
            'file_thumbnail_height'=>'intval',
        );
    }
}
