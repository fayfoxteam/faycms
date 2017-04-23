<?php
namespace cms\widgets\jq_nivo_slider\controllers;

use fay\widget\Widget;
use cms\services\FlashService;

class AdminController extends Widget{
    public function initConfig($config){
        empty($config['files']) && $config['files'] = array();
        isset($config['element_id']) || $config['element_id'] = '';
        isset($config['animSpeed']) || $config['animSpeed'] = 500;
        isset($config['pauseTime']) || $config['pauseTime'] = 5000;
        isset($config['effect']) || $config['effect'] = 'random';
        isset($config['directionNav']) || $config['directionNav'] = 1;
        isset($config['width']) || $config['width'] = 0;
        isset($config['height']) || $config['height'] = 0;
        
        //设置模版
        $this->parseTemplateForEdit($config);
        
        return $this->config = $config;
    }
    
    public function index(){
        $this->view->render();
    }
    
    public function onPost(){
        $data = $this->form->getFilteredData();
        
        $files = $this->input->post('files', 'intval', array());
        $links = $this->input->post('links', 'trim');
        $titles = $this->input->post('titles', 'trim');
        $start_times = $this->input->post('start_time', 'trim|strtotime');
        $end_times = $this->input->post('end_time', 'trim|strtotime');
        foreach($files as $p){
            $data['files'][] = array(
                'file_id'=>$p,
                'link'=>$links[$p],
                'title'=>$titles[$p],
                'start_time'=>$start_times[$p] ? $start_times[$p] : 0,
                'end_time'=>$end_times[$p] ? $end_times[$p] : 0,
            );
        }

        $this->saveConfig($data);
        FlashService::set('编辑成功', 'success');
    }
    
    public function rules(){
        return array(
            array('links', 'url'),
            array(array('animSpeed', 'pauseTime', 'width', 'height'), 'int', array('min'=>1)),
            array('element_id', 'string', array('format'=>'alias')),
        );
    }
    
    public function labels(){
        return array(
            'element_id'=>'外层元素ID',
            'links'=>'链接地址',
            'pauseTime'=>'停顿时长',
            'animSpeed'=>'过渡动画时长',
            'width'=>'图片宽度',
            'height'=>'图片高度',
            'start_time'=>'生效时间',
            'end_time'=>'过期时间',
        );
    }
    
    public function filters(){
        return array(
            'element_id'=>'trim',
            'animSpeed'=>'intval',
            'pauseTime'=>'intval',
            'effect'=>'trim',
            'directionNav'=>'intval',
            'width'=>'intval',
            'height'=>'intval',
            'template'=>'trim',
            'template_code'=>'trim',
        );
    }
}