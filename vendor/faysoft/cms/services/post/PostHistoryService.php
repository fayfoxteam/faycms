<?php
namespace cms\services\post;

use fay\core\Service;

/**
 * 文章历史
 */
class PostHistoryService extends Service{
    /**
     * @param string $class_name
     * @return PostHistoryService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 创建历史
     * @param array $post
     * @param null|int $user_id
     */
    public function create($post, $user_id = null){
        
    }
    
    /**
     * 根据文章ID，获取文章历史
     * @param int $post_id 文章ID
     * @param string $fields 指定字段
     * @param int $limit 获取历史数量
     * @param int $last_id 用于分页
     */
    public function getPostHistory($post_id, $fields = '*', $limit = 10, $last_id = 0){
        
    }
}