<?php
namespace fay\models\tables;

use fay\core\db\Table;

class UsersNotificationsTable extends Table{
    protected $_name = 'users_notifications';
    protected $_primary = array('user_id', 'notification_id');
    
    /**
     * @param string $class_name
     * @return UsersNotificationsTable
     */
    public static function model($class_name = __CLASS__){
        return parent::model($class_name);
    }
    
    public function rules(){
        return array(
            array(array('user_id', 'notification_id'), 'int', array('min'=>0, 'max'=>4294967295)),
            array(array('read', 'processed', 'ignored'), 'int', array('min'=>-128, 'max'=>127)),
            array(array('option'), 'string', array('max'=>255)),
            array(array('delete_time'), 'range', array('range'=>array(0, 1))),
        );
    }

    public function labels(){
        return array(
            'user_id'=>'收件人',
            'notification_id'=>'消息ID',
            'read'=>'已读状态',
            'delete_time'=>'删除时间',
            'processed'=>'是否处理',
            'ignored'=>'是否忽略',
            'option'=>'附加参数',
        );
    }

    public function filters(){
        return array(
            'user_id'=>'intval',
            'notification_id'=>'intval',
            'read'=>'intval',
            'delete_time'=>'intval',
            'processed'=>'intval',
            'ignored'=>'intval',
            'option'=>'trim',
        );
    }
}