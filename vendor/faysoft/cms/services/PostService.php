<?php
namespace cms\services;

use fay\core\Service;
use fay\models\tables\PostsTable;

class PostService extends Service{
    /**
     * @param string $class_name
     * @return PostService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 根据文章状态获取文章数
     * @param int $status 文章状态
     * @return string
     */
    public function getCount($status = null){
        $conditions = array('delete_time = 0');
        if($status !== null){
            $conditions['status = ?'] = $status;
        }
        $result = PostsTable::model()->fetchRow($conditions, 'COUNT(*)');
        return $result['COUNT(*)'];
    }
    
    /**
     * 获取已删除的文章数
     * @return string
     */
    public function getDeletedCount(){
        $result = PostsTable::model()->fetchRow('delete_time > 0', 'COUNT(*)');
        return $result['COUNT(*)'];
    }
}