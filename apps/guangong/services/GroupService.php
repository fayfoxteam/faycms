<?php
namespace guangong\services;

use cms\services\user\UserService;
use fay\core\Loader;
use fay\core\Service;
use guangong\models\tables\GuangongUserGroupUsersTable;

class GroupService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 判断用户是否属于某个结义
     * @param int $group_id
     * @param null|int $user_id
     * @return bool
     */
    public static function inGroup($group_id, $user_id = null){
        $user_id = UserService::makeUserID($user_id);
        
        return !!GuangongUserGroupUsersTable::model()->fetchRow(array(
            'group_id = ?'=>$group_id,
            'user_id = ?'=>$user_id,
        ));
    }
}