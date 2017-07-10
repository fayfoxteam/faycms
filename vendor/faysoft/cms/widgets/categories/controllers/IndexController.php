<?php
namespace cms\widgets\categories\controllers;

use cms\helpers\LinkHelper;
use cms\services\CategoryService;
use fay\widget\Widget;

class IndexController extends Widget{
    public function initConfig($config){
        //root node
        if(empty($config['top'])){
            $root_node = CategoryService::service()->get('_system_post', 'id');
            $config['top'] = $root_node['id'];
        }
        
        //title
        if(empty($config['title'])){
            $node = CategoryService::service()->get($config['top'], 'title');
            $config['title'] = $node['title'];
        }
        
        $config['show_sibling_when_terminal'] = !empty($config['show_sibling_when_terminal']);
        
        return $this->config = $config;
    }
    
    public function getData(){
        //确定顶级分类
        if(!empty($this->config['cat_key']) && $this->input->get($this->config['cat_key'])){
            $top_cat = $this->input->get($this->config['cat_key'], 'trim');
        }else{
            $top_cat = $this->config['top'];
        }
    
        if($this->config['show_sibling_when_terminal']){//若开启了无子分类展示平级分类功能，搜索顶级分类判断其是否有子分类
            $top_cat_info = CategoryService::service()->get($top_cat, 'parent,left_value,right_value');
            if($top_cat_info['right_value'] - $top_cat_info['left_value'] == 1){
                $cats = CategoryService::service()->getSiblingByArray($top_cat_info);
            }
        }
        
        if(empty($cats)){//未开启无子分类展示平级分类功能，或当前分类有子分类，正常获取子分类
            if(!empty($this->config['hierarchical'])){
                $cats = CategoryService::service()->getTree($top_cat);
            }else{
                $cats = CategoryService::service()->getChildren($top_cat);
            }
        }
        
        //格式化分类链接
        $cats = $this->assembleLink($cats);
        
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
     * @return array
     */
    private function assembleLink($cats){
        foreach($cats as &$c){
            $c['link'] = LinkHelper::generateCatLink($c);
            
            if(!empty($c['children'])){
                $c['children'] = $this->assembleLink($c['children']);
            }
        }
        
        return $cats;
    }
}