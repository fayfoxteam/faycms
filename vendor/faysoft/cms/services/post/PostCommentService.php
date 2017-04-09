<?php
namespace cms\services\post;

use fay\core\Service;
use fay\models\tables\PostCommentsTable;

class PostCommentService extends Service{
    /**
     * @param string $class_name
     * @return PostCommentService
     */
    public static function service($class_name=__CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 根据状态，获取文章评论数
     * @param int $status
     * @return string
     */
    public function getCount($status = null){
        $conditions = array('delete_time = 0');
        if($status !== null){
            $conditions['status = ?'] = $status;
        }
        $result = PostCommentsTable::model()->fetchRow(array(
            'delete_time = 0',
            'status = ?'=>$status ? $status : false,
        ), 'COUNT(*)');
        return $result['COUNT(*)'];
    }
    
    /**
     * 获取回收站内文章评论数
     * @return string
     */
    public function getDeletedCount(){
        $result = PostCommentsTable::model()->fetchRow(array('delete_time > 0'), 'COUNT(*)');
        return $result['COUNT(*)'];
    }    
}