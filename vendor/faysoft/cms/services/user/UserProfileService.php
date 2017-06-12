<?php
namespace cms\services\user;

use fay\core\Loader;
use fay\core\Service;
use cms\models\tables\UserProfileTable;
use fay\helpers\FieldsHelper;

class UserProfileService extends Service{
    /**
     * 可返回字段
     */
    public static $default_fields = array(
        'reg_time', 'last_login_time', 'last_login_ip', 'last_time_online'
    );
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 获取用户信息
     * @param int $user_id 用户ID
     * @param string $fields 附件字段（user_profile表字段）
     * @return array 返回包含用户profile信息的二维数组
     */
    public function get($user_id, $fields = null){
        $fields = new FieldsHelper(
            $fields ? $fields : self::$default_fields,
            '',
            UserProfileTable::model()->getFields()
        );
        
        return UserProfileTable::model()->fetchRow(array(
            'user_id = ?'=>$user_id,
        ), $fields->getFields());
    }
    
    /**
     * 批量获取用户信息
     * @param array $user_ids 用户ID一维数组
     * @param string $fields 附件字段（user_profile表字段）
     * @return array 返回以用户ID为key的三维数组
     */
    public function mget($user_ids, $fields = null){
        $fields = new FieldsHelper(
            $fields ? $fields : self::$default_fields,
            '',
            UserProfileTable::model()->getFields()
        );
        
        if(!$fields->hasField('user_id')){
            $fields->addFields('user_id');
            $remove_user_id = true;
        }else{
            $remove_user_id = false;
        }
        $profiles = UserProfileTable::model()->fetchAll(array(
            'user_id IN (?)'=>$user_ids,
        ), $fields->getFields(), 'user_id');
        $return = array_fill_keys($user_ids, array());
        foreach($profiles as $p){
            $u = $p['user_id'];
            if($remove_user_id){
                unset($p['user_id']);
            }
            $return[$u] = $p;
        }
        return $return;
    }
}