<?php
namespace cms\services\user;

use fay\core\Loader;
use fay\core\Service;
use cms\models\tables\UserCounterTable;
use fay\helpers\FieldsHelper;

class UserCounterService extends Service{
    /**
     * 可返回字段
     */
    public static $default_fields = array(
        'posts', 'follows', 'fans', 'messages'
    );
    
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 递增一个或多个指定用户的计数
     * @param array|int $user_ids
     * @param string $field user_counter表对应的列名
     * @param int $value 增量，默认为1，正数表示递减
     * @return int
     */
    public function incr($user_ids, $field, $value = 1){
        if(!$user_ids){
            return 0;
        }
        if(!is_array($user_ids)){
            $user_ids = explode(',', $user_ids);
        }
        
        return UserCounterTable::model()->incr(array(
            'user_id IN (?)'=>$user_ids,
        ), $field, $value);
    }
    
    /**
     * 递减一个或多个指定用户的计数
     * @param array|int $user_ids
     * @param string $field user_counter表对应的列名
     * @param int $value 增量，默认为1，正数表示递减
     * @return int
     */
    public function decr($user_ids, $field, $value = 1){
        return $this->incr($user_ids, $field, -$value);
    }
    
    /**
     * 获取用户信息
     * @param int $user_id 用户ID
     * @param string $fields
     * @return array 返回包含用户profile信息的二维数组
     */
    public function get($user_id, $fields = null){
        $fields = new FieldsHelper(
            $fields ? $fields : self::$default_fields,
            '',
            UserCounterTable::model()->getFields()
        );
        
        return UserCounterTable::model()->fetchRow(array(
            'user_id = ?'=>$user_id,
        ), $fields->getFields());
    }
    
    /**
     * 批量获取用户信息
     * @param array $user_ids 用户ID一维数组
     * @param string $fields
     * @return array 返回以用户ID为key的三维数组
     */
    public function mget($user_ids, $fields = null){
        $fields = new FieldsHelper(
            $fields ? $fields : self::$default_fields,
            '',
            UserCounterTable::model()->getFields()
        );
        
        if($fields->hasField('user_id')){
            $fields->addFields('user_id');
            $remove_user_id = true;
        }else{
            $remove_user_id = false;
        }
        $counters = UserCounterTable::model()->fetchAll(array(
            'user_id IN (?)'=>$user_ids,
        ), $fields->getFields(), 'user_id');
        $return = array_fill_keys($user_ids, array());
        foreach($counters as $c){
            $u = $c['user_id'];
            if($remove_user_id){
                unset($c['user_id']);
            }
            $return[$u] = $c;
        }
        return $return;
    }
}