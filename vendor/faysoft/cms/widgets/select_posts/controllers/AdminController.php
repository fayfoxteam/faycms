<?php
namespace cms\widgets\select_posts\controllers;

use cms\services\post\PostService;
use fay\helpers\ArrayHelper;
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
        $this->view->posts = PostService::service()->mget(
            ArrayHelper::column($this->config['posts'], 'post_id'),
            'id,status,title,publish_time,thumbnail,user.nickname,category.title',
            false,
            true
        );
        
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

        $posts = $this->input->post('posts', 'intval', array());
        $start_times = $this->input->post('start_time', 'trim|strtotime');
        $end_times = $this->input->post('end_time', 'trim|strtotime');

        foreach($posts as $p){
            $data['posts'][] = array(
                'post_id'=>$p,
                'start_time'=>$start_times[$p] ? $start_times[$p] : 0,
                'end_time'=>$end_times[$p] ? $end_times[$p] : 0,
            );
        }
        
        $this->saveConfig($data);
        
        FlashService::set('编辑成功', 'success');
    }
    
    public function rules(){
        return array(
            array(array('file_thumbnail_width', 'file_thumbnail_height', 'post_thumbnail_width', 'post_thumbnail_height'), 'int', array('min'=>0)),
            array(array('start_time', 'end_time'), 'datetime'),
            array(array('posts'), 'intval', array('min'=>1)),
        );
    }
    
    public function labels(){
        return array(
            'post_thumbnail_width'=>'文章缩略图宽度',
            'post_thumbnail_height'=>'文章缩略图高度',
            'file_thumbnail_width'=>'附件缩略图宽度',
            'file_thumbnail_height'=>'附件缩略图高度',
            'start_time'=>'生效时间',
            'end_time'=>'过期时间',
            'posts'=>'文章ID',
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
        );
    }
}