<?php 
namespace cms\helpers;

use cms\models\tables\PostsTable;
use cms\services\CategoryService;
use cms\services\OptionService;
use fay\core\ErrorException;
use fay\helpers\NumberHelper;
use fay\helpers\UrlHelper;

/**
 * 生成链接
 */
class LinkHelper{
    /**
     * 获取文章链接
     * 支持变量有{$id}, {$cat_id}, {$date:xx}, {$cat_alias}
     * @param array|int $post
     * @return string
     * @throws ErrorException
     */
    public static function getPostLink($post){
        if(NumberHelper::isInt($post)){
            $post = array(
                'id'=>$post,
            );
        }
        if(!isset($post['id'])){
            throw new ErrorException('必须传入文章id或包含文章id的数组');
        }
        
        $uri = OptionService::get('system:post_uri', 'post/{$id}');
        preg_match_all('/{\$([\w:]+)}/', $uri, $matches);
        if(empty($matches)){
            throw new ErrorException('系统未设置uri或uri未包含任何变量，无法生成文章链接');
        }
    
        foreach($matches[1] as $param){
            if($param == 'id'){
                $uri = str_replace('{$id}', $post['id'], $uri);
            }else if($param == 'cat_id'){
                if(!isset($post['cat_id'])){
                    //传入的$post包含的信息不足，搜索数据库
                    $post = PostsTable::model()->find($post['id'], 'id,cat_id,publish_time');
                }
                $uri = str_replace('{$cat_id}', $post['cat_id'], $uri);
            }else if($param == 'cat_alias'){
                if(!isset($post['cat_id'])){
                    $post = PostsTable::model()->find($post['id'], 'id,cat_id,publish_time');
                }
                $cat_alias = CategoryService::service()->getAliasById($post['cat_id']);
                $uri = str_replace('{$cat_alias}', $cat_alias, $uri);
            }else if(preg_match('/date:[Yymn]+/', $param)){
                if(!isset($post['publish_time'])){
                    //传入的$post包含的信息不足，搜索数据库
                    $post = PostsTable::model()->find($post['id'], 'id,cat_id,publish_time');
                }
                $uri = str_replace('{$' . $param . '}', date(substr($param, 5), $post['publish_time']), $uri);
            }else{
                throw new ErrorException('系统设置的uri包含无法识别的变量，生成文章链接失败');
            }
        }
        
        return UrlHelper::createUrl($uri);
    }
}