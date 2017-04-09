<?php
namespace apidoc\helpers;

use apidoc\services\ModelService;
use fay\helpers\HtmlHelper;

class SampleHelper{
    /**
     * 解析示例代码
     * @param string $json json文本
     * @return string
     */
    public static function render($json){
        return HtmlHelper::encode(self::replaceModel($json));
    }
    
    /**
     * 递归的将json中类似`{{@User}}`这样的占位符，替换为对应model的示例代码
     * @param string $json
     * @return string
     */
    public static function replaceModel($json){
        return preg_replace_callback('/"{{@(\w+)}}"/', function($matches){
            $sample = ModelService::service()->getSample($matches[1]);
            
            return $sample ? SampleHelper::replaceModel($sample) : $matches[0];
        }, $json);
    }
}