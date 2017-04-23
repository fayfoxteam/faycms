<?php
namespace cms\helpers;

use fay\helpers\LocalFileHelper;

class WidgetHelper{
    /**
     * 获取可能用于widget的模版文件
     * 理论上widget可以指定任何路径的模版文件，为了方便，一般
     */
    public static function getViews(){
        $modules = LocalFileHelper::getFileList(APPLICATION_PATH . 'modules');
        
        $views = array();
        foreach($modules as $module){
            if(!$module['is_dir']){
                //非目录则跳过
                continue;
            }
            
            $module_views = LocalFileHelper::getFileList($module['path'] . DS . 'views' . DS . 'widget');
            foreach($module_views as $mv){
                if(!$mv['is_dir'] && substr($mv['name'], -4) == '.php'){
                    $views[] = 'frontend/widget/' . substr($mv['name'], 0, -4);
                }
            }
        }
        
        return $views;
    }

    /**
     * 根据路由标识符获取view文件内容
     * @param string $router
     * @return string|false
     */
    public static function getViewByRouter($router){
        $router_arr = explode('/', $router, 4);
        if(count($router_arr) == 3 && file_exists(APPLICATION_PATH . "modules/{$router_arr[0]}/views/{$router_arr[1]}/{$router_arr[2]}.php")){
            return file_get_contents(APPLICATION_PATH . "modules/{$router_arr[0]}/views/{$router_arr[1]}/{$router_arr[2]}.php");
        }else if(count($router_arr) == 4 && $router_arr[0] == APPLICATION && file_exists(APPLICATION_PATH . "modules/{$router_arr[1]}/views/{$router_arr[2]}/{$router_arr[3]}.php")){
            return file_get_contents(APPLICATION_PATH . "modules/{$router_arr[1]}/views/{$router_arr[2]}/{$router_arr[3]}.php");
        }else{
            return false;
        }
    }
}