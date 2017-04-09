<?php
namespace guangong\services;

use fay\core\ErrorException;
use fay\core\Service;
use guangong\models\tables\GuangongUserGroupUsersTable;

class GroupService extends Service{
    /**
     * @param string $class_name
     * @return GroupService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    /**
     * 判断用户是否属于某个结义
     * @param int $group_id
     * @param null|int $user_id
     * @return bool
     * @throws ErrorException
     */
    public static function inGroup($group_id, $user_id = null){
        $user_id === null && $user_id = \F::app()->current_user;
        if(!$user_id){
            throw new ErrorException('未指定用户ID');
        }
        
        return !!GuangongUserGroupUsersTable::model()->fetchRow(array(
            'group_id = ?'=>$group_id,
            'user_id = ?'=>$user_id,
        ));
    }
}