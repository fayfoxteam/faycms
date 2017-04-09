<?php
namespace cms\widgets\menu\controllers;

use fay\helpers\HtmlHelper;
use fay\widget\Widget;
use cms\services\MenuService;
use cms\models\tables\MenusTable;

class IndexController extends Widget{
    public function initConfig($config){
        empty($config['top']) && $config['top'] = MenusTable::ITEM_USER_MENU;
        
        return $this->config = $config;
    }
    
    public function getData(){
        $menus = MenuService::service()->getTree($this->config['top'], true, true);
        $this->removeFields($menus);
        return $menus;
    }
    
    /**
     * 移除一些对客户端没用的字段
     * @param array $menus
     */
    private function removeFields(&$menus){
        foreach($menus as &$m){
            unset($m['left_value'], $m['right_value'], $m['sort']);
            if(!empty($m['children'])){
                $this->removeFields($m['children']);
            }
        }
    }
    
    public function index(){
        $menus = $this->getData();
        
        //若无导航可显示，则不显示该widget
        if(empty($menus)){
            return;
        }
        
        $this->renderTemplate(array(
            'menus'=>$menus
        ));
    }
    
    /**
     * 输出导航栏
     * @param $menus
     */
    public function renderMenu($menus){
        echo '<ul>';
        foreach($menus as $m){
            echo '<li'.(empty($m['children']) ? '' : ' class="has-sub"').'>';
            echo HtmlHelper::link('<span class="title">'.$m['title'].'</span>', $m['link'], array(
                'encode'=>false,
                'title'=>false,
                'prepend'=>$m['css_class'] ? array(
                    'tag'=>'i',
                    'text'=>'',
                    'class'=>$m['css_class'],
                ) : false,
            ));
            if(!empty($m['children'])){
                $this->renderMenu($m['children']);
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}