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
}