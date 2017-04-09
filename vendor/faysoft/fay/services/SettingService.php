<?php
namespace fay\services;

use fay\core\Service;
use fay\models\tables\UserSettingsTable;

class SettingService extends Service{
    /**
     * @param string $class_name
     * @return SettingService
     */
    public static function service($class_name = __CLASS__){
        return parent::service($class_name);
    }
    
    public function set($key, $value, $user_id = null){
        if(UserSettingsTable::model()->fetchRow(array(
            'user_id = ?'=>$user_id ? $user_id : \F::app()->current_user,
            'setting_key = ?'=>$key,
        ), 'setting_key')){
            UserSettingsTable::model()->update(array(
                'setting_value'=>json_encode($value),
            ), array(
                'user_id = ?'=>$user_id ? $user_id : \F::app()->current_user,
                'setting_key = ?'=>$key,
            ));
        }else{
            UserSettingsTable::model()->insert(array(
                'user_id'=>$user_id ? $user_id : \F::app()->current_user,
                'setting_key'=>$key,
                'setting_value'=>json_encode($value),
            ));
        }
    }
    
    public function get($key, $user_id = null){
        $setting = UserSettingsTable::model()->fetchRow(array(
            'user_id = ?'=>$user_id ? $user_id : \F::app()->current_user,
            'setting_key = ?'=>$key,
        ), 'setting_value');
        if($setting !== false){
            return json_decode($setting['setting_value'], true);
        }else{
            return null;
        }
    }
}