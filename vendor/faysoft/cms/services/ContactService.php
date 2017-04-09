<?php
namespace cms\services;

use fay\core\Service;
use fay\helpers\RequestHelper;
use cms\models\tables\ContactsTable;

class ContactService extends Service{
    /**
     * 留言创建后事件
     */
    const EVENT_CREATED = 'after_contact_created';
    
    /**
     * @param string $class_name
     * @return ContactService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 添加一条留言
     * @param array $data 用户数据
     * @param int $is_show 是否前端显示
     * @param int $is_read 是否已读
     */
    public function create($data, $is_show = 1, $is_read = 0){
        //过滤数据
        $data = ContactsTable::model()->fillData($data, true, array('reply'));
        
        //附加默认字段
        $data = array_merge($data, array(
            'ip_int'=>RequestHelper::ip2int(\F::app()->ip),
            'show_ip_int'=>RequestHelper::ip2int(\F::app()->ip),
            'create_time'=>\F::app()->current_time,
            'publish_time'=>\F::app()->current_time,
            'is_show'=>$is_show,
            'is_read'=>$is_read,
        ));
        
        //入库
        $contact_id = ContactsTable::model()->insert($data);
        
        \F::event()->trigger(self::EVENT_CREATED, $contact_id);
    }
}