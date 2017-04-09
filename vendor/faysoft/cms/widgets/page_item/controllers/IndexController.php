<?php
namespace cms\widgets\page_item\controllers;

use fay\widget\Widget;
use cms\services\PageService;
use fay\core\HttpException;
use cms\models\tables\PagesTable;

class IndexController extends Widget{
    public function initConfig($config){
        isset($config['id_key']) || $config['id_key'] = 'page_id';
        isset($config['alias_key']) || $config['alias_key'] = 'page_alias';
        empty($config['default_page_id']) && $config['default_page_id'] = 0;
        $config['inc_views'] = empty($config['inc_views']) ? 0 : 1;
        
        return $this->config = $config;
    }
    
    public function getData(){
        if(!empty($this->config['id_key']) && $this->input->get($this->config['id_key'])){
            //根据页面ID访问
            $page = PageService::service()->get($this->input->get($this->config['id_key'], 'intval'));
            if(!$page){
                throw new HttpException('您访问的页面不存在');
            }
        }else if(!empty($this->config['alias_key']) && $this->input->get($this->config['alias_key'])){
            //根据页面别名访问
            $page = PageService::service()->get($this->input->get($this->config['alias_key'], 'trim'));
            if(!$page){
                throw new HttpException('您访问的页面不存在');
            }
        }else if($this->config['default_page_id']){
            //默认显示页面（若默认页面不存在，则返回空，不报错）
            $page = PageService::service()->get($this->config['default_page_id']);
            if(!$page){
                return array();
            }
        }else{
            //若未设置默认显示页面，则返回空，不报错
            return array();
        }
        
        if($this->config['inc_views']){
            //递增浏览量
            PagesTable::model()->incr($page['id'], 'views', 1);
        }
        
        return $page;
    }
    
    public function index(){
        $page = $this->getData();
        
        if($page){
            $this->renderTemplate(array(
                'page'=>$page,
            ));
        }
    }
}