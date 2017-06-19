<?php
namespace cms\services;

use cms\models\tables\UserSettingsTable;
use fay\core\Loader;
use fay\core\Service;

class SettingService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
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