<?php
namespace cms\services;

use cms\models\tables\LogsTable;
use fay\core\Loader;
use fay\core\Service;
use fay\helpers\RequestHelper;

class LogService extends Service{
    /**
     * @return $this
     */
    public static function service(){
        return Loader::singleton(__CLASS__);
    }
    
    /**
     * 记录日志
     * @param string $code 错误码
     * @param mixed $data 相关数据(若为数组，会被转为json存储)
     * @param int $type 错误级别，在Logs中定义错误级别常量
     */
    public static function set($code, $data, $type = LogsTable::TYPE_NORMAL){
        LogsTable::model()->insert(array(
            'code'=>$code,
            'data'=>is_array($data) ? json_encode($data) : $data,
            'type'=>$type,
            'user_id'=>isset(\F::app()->current_user) ? \F::app()->current_user : 0,
            'create_time'=>\F::app()->current_time,
            'create_date'=>date('Y-m-d'),
            'ip_int'=>RequestHelper::ip2int(\F::app()->ip),
            'user_agent'=>isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
        ));
    }
    
    public static function getType($type){
        switch($type){
            case LogsTable::TYPE_ERROR:
                return '<span class="fc-red">错误</span>';
            break;
            case LogsTable::TYPE_NORMAL:
                return '<span>正常</span>';
            break;
            case LogsTable::TYPE_WARMING:
                return '<span class="fc-orange">警告</span>';
            break;
            default:
                return '<span>未知</span>';
            break;
        }
    }
}