<?php
namespace cms\helpers;

use fay\helpers\HtmlHelper;
use fay\core\Uri;
use fay\helpers\UrlHelper;

class MenuHelper{
    /**
     * 渲染一个导航栏
     * @param array $menus 菜单集
     * @param string $current_directory 当前页
     * @param int|number $dep 深度
     * @return mixed
     */
    public static function render($menus, $current_directory, $dep = 0){
        $text = array();
        foreach($menus as $m){
            //以link属性是否为javascript:来判断是否为叶子
            //非叶子，但却没有叶子被启用，不显示该节点
            if($m['link'] == 'javascript:' && empty($m['children'])){
                continue;
            }
            
            //叶子节点，进行权限检查
            if($m['link'] != 'javascript:' && !\F::app()->checkPermission($m['link'])){
                continue;
            }
            
            $item = array(
                'tag'=>'li',
                'class'=>array(),
                'text'=>array(
                    array(
                        'tag'=>'a',
                        'href'=>$m['link'] == 'javascript:' ? 'javascript:'
                            //后台菜单配置比较特殊，系统自带的只有router部分，用户自定义部分可能会有完整url
                            : (strpos($m['link'], 'http://') === 0 ? $m['link'] : UrlHelper::createUrl($m['link'])),
                        'text'=>array(
                            //小图标
                            $m['css_class'] ? array(
                                'tag'=>'i',
                                'text'=>'',
                                'class'=>$m['css_class'],
                            ) : false,
                            array(
                                'tag'=>'span',
                                'class'=>'title',
                                'text'=>$m['title'],
                            )
                        ),
                        'target'=>$m['target'] ? $m['target'] : false,
                    ),
                    //子菜单
                    empty($m['children']) ? false : self::render($m['children'], $current_directory, $dep + 1),
                )
            );
            
            //由于权限关系，非叶子节点却没有下级菜单可以被显示，则该菜单也不显示
            if($m['link'] != 'javascript:' || ($m['link'] == 'javascript:' && !empty($item['text'][1]))){
                if(!empty($m['children'])){
                    $item['class'][] = 'has-sub';
                }
                if(($current_directory && $current_directory == $m['alias']) || Uri::getInstance()->router == $m['link']){
                    $item['class'][] = 'opened';
                    $item['class'][] = 'expanded';
                    $item['class'][] = 'active';
                }
                
                //如果有孩子节点被打开，则父节点也被打开
                if(!in_array('active', $item['class']) && is_array($item['text'][1])){
                    foreach($item['text'][1]['text'] as $i){
                        if(in_array('active', $i['class'])){
                            $item['class'][] = 'opened';
                            $item['class'][] = 'expanded';
                            $item['class'][] = 'active';
                            break;
                        }
                    }
                }
                
                $text[] = $item;
            }
        }
        
        if($dep){
            if($text){
                return array(
                    'tag'=>'ul',
                    'text'=>$text,
                );
            }else{
                return false;
            }
        }else{
            echo HtmlHelper::tag('ul', array(
                'class'=>'main-menu',
                'id'=>'main-menu',
            ), $text);
        }
    }
}