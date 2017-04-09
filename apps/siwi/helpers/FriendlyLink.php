<?php 
namespace siwi\helpers;

class FriendlyLink{
    /**
     * 参数值为-1，视为跟随get参数
     * 其他值则进行设置
     */
    public static function get($type, $cat_1 = -1, $cat_2 = -1, $time = -1, $page = 1){
        $params = self::getParams();
        if($cat_1 == -1 && isset($params['cat_1'])){
            $cat_1 = $params['cat_1'];
        }
        if($cat_2 == -1 && isset($params['cat_2'])){
            $cat_2 = $params['cat_2'];
        }
        if($time == -1 && isset($params['time'])){
            $time = $params['time'];
        }
        
        //出于扩展考虑，有2个占位符
        return \F::app()->view->url("{$type}/{$cat_1}-{$cat_2}-{$time}-0-0-{$page}");
    }
    
    /**
     * 解析一串参数
     * 出于扩展考虑，有2个占位符
     */
    public static function getParams(){
        $labels = array(
            'cat_1', 'cat_2', 'time', 'placeholder_1', 'placeholder_2', 'page',
        );
        if(\F::app()->input->get('params')){
            $params = explode('-', \F::app()->input->get('params'));
        }else{
            $params = array(0, 0, 0, 0, 0, 0);
        }
        foreach($params as &$p){
            $p = intval($p);
        }
    
        return array_combine($labels, $params);
    }
}