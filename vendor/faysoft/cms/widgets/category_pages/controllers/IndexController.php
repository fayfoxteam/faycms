<?php
namespace cms\widgets\category_pages\controllers;

use cms\helpers\LinkHelper;
use cms\services\CategoryService;
use fay\core\Sql;
use fay\widget\Widget;

class IndexController extends Widget{
    public function initConfig($config){
        //root node
        if(empty($config['top'])){
            $root_node = CategoryService::service()->getByAlias('_system_page', 'id');
            $config['top'] = $root_node['id'];
        }
        
        //number
        empty($config['number']) && $config['number'] = 5;
        
        //title
        if(empty($config['title'])){
            $node = CategoryService::service()->get($config['top'], 'title');
            $config['title'] = $node['title'];
        }
        
        //show_empty
        isset($config['show_empty']) || $config['show_empty'] = '0';
        
        //设置模版
        $this->parseTemplateForEdit($config);
        
        return $this->config = $config;
    }
    
    public function getData(){
        $sql = new Sql();
        $pages = $sql->from(array('pc'=>'pages_categories'), '')
            ->joinLeft(array('p'=>'pages'), 'pc.page_id = p.id', 'id,title,alias,thumbnail,abstract')
            ->where(array('pc.cat_id = ?'=>$this->config['top']))
            ->fetchAll();
        
        foreach($pages as &$p){
            $p['link'] = LinkHelper::generatePageLink($p);
        }
        
        return $pages;
    }
    
    public function index(){
        $pages = $this->getData();
        
        if(!$pages && !$this->config['show_empty']){
            return;
        }
        
        $this->renderTemplate(array(
            'pages'=>$pages,
        ));
    }
}