<?php
namespace cms\services;

use fay\core\Service;
use fay\models\tables\FeedsTable;

class FeedService extends Service{
    /**
     * @param string $class_name
     * @return FeedService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 根据动态状态获取动态数
     * @param int $status 动态状态
     */
    public function getCount($status = null){
        $conditions = array('delete_time = 0');
        if($status !== null){
            $conditions['status = ?'] = $status;
        }
        $result = FeedsTable::model()->fetchRow($conditions, 'COUNT(*)');
        return $result['COUNT(*)'];
    }
    
    /**
     * 获取已删除的动态数
     */
    public function getDeletedCount(){
        $result = FeedsTable::model()->fetchRow('delete_time > 0', 'COUNT(*)');
        return $result['COUNT(*)'];
    }
}