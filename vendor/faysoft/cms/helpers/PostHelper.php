<?php 
namespace cms\helpers;

use cms\models\tables\PostsTable;
use cms\services\OptionService;
use fay\core\ErrorException;
use fay\helpers\NumberHelper;
use fay\helpers\UrlHelper;

class PostHelper{
    /**
     * 获取文章状态
     * @param int $status 文章状态码
     * @param int $delete 是否删除
     * @param bool $coloring 是否着色（带上html标签）
     * @return string
     */
    public static function getStatus($status, $delete, $coloring = true){
        if($delete == 1){
            if($coloring)
                return '<span class="fc-red">回收站</span>';
            else
                return '回收站';
        }
        switch ($status) {
            case PostsTable::STATUS_PUBLISHED:
                return '已发布';
                break;
            case PostsTable::STATUS_DRAFT:
                if($coloring)
                    return '<span class="fc-blue">草稿</span>';
                else
                    return '草稿';
                break;
            case PostsTable::STATUS_PENDING:
                if($coloring)
                    return '<span class="fc-orange">待审核</span>';
                else
                    return '待审核';
                break;
            case PostsTable::STATUS_REVIEWED:
                if($coloring)
                    return '<span class="fc-green">通过审核</span>';
                else
                    return '通过审核';
                break;
            default:
                if($coloring)
                    return '<span class="fc-yellow">未知的状态</span>';
                else
                    return '未知的状态';
                break;
        }
    }
    
    /**
     * 获取文章链接
     * 支持变量有{$id}, {$cat_id}, {$date:xx}
     * @param array|int $post
     * @return string
     * @throws ErrorException
     */
    public static function getLink($post){
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