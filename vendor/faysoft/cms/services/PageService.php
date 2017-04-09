<?php
namespace cms\services;

use fay\core\Service;
use fay\models\tables\PagesTable;

class PageService extends Service{
    /**
     * @param string $class_name
     * @return PageService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 根据页面状态获取页面数
     * @param int $status 页面状态
     * @return string
     */
    public function getCount($status = null){
        $conditions = array('delete_time = 0');
        if($status !== null){
            $conditions['status = ?'] = $status;
        }
        $result = PagesTable::model()->fetchRow($conditions, 'COUNT(*)');
        return $result['COUNT(*)'];
    }
    
    /**
     * 获取已删除的页面数
     * @return string
     */
    public function getDeletedCount(){
        $result = PagesTable::model()->fetchRow('delete_time > 0', 'COUNT(*)');
        return $result['COUNT(*)'];
    }
}