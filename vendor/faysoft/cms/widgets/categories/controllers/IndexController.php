<?php
namespace cms\widgets\categories\controllers;

use fay\widget\Widget;
use cms\services\CategoryService;

class IndexController extends Widget{
    public function initConfig($config){
        //root node
        if(empty($config['top'])){
            $root_node = CategoryService::service()->getByAlias('_system_post', 'id');
            $config['top'] = $root_node['id'];
        }
        
        //title
        if(empty($config['title'])){
            $node = CategoryService::service()->get($config['top'], 'title');
            $config['title'] = $node['title'];
        }
        
        //uri
        if(empty($config['uri'])){
            $config['uri'] = 'cat/{$id}';
        }
        
        //设置模版
        empty($config['template']) && $config['template'] = $this->getDefaultTemplate();
        
        return $this->config = $config;
    }
    
    public function getData(){
        //确定顶级分类
        if(!empty($this->config['cat_key']) && $this->input->get($this->config['cat_key'])){
            $top_cat = $this->input->get($this->config['cat_key'], 'intval');
        }else{
            $top_cat = $this->config['top'];
        }
        if(!empty($this->config['hierarchical'])){
            $cats = CategoryService::service()->getTree($top_cat);
        }else{
            $cats = CategoryService::service()->getChildren($top_cat);
        }
        
        //格式化分类链接
        $cats = $this->setLink($cats, $this->config['uri']);
        
        return $cats;
    }
    
    public function index(){
        $cats = $this->getData();
        
        //若无分类可显示，则不显示该widget
        if(empty($cats)){
            return;
        }
        
        $this->renderTemplate(array(
            'cats'=>$cats,
        ));
    }
    
    /**
     * 为分类列表添加link字段
     * @param array $cats
     * @param string $uri
     * @return array
     */
    private function setLink($cats, $uri){
        foreach($cats as &$c){
            $c['link'] = $this->view->url(str_replace(array(
                '{$id}', '{$alias}',
            ), array(
                $c['id'], $c['alias'],
            ), $uri));
            
            if(!empty($c['children'])){
                $c['children'] = $this->setLink($c['children'], $uri);
            }
        }
        
        return $cats;
    }
}