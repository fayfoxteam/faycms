<?php
namespace fay\models;

use fay\core\Model;
use fay\models\tables\Logs;
use fay\helpers\Request;

class Log extends Model{
	/**
	 * @param string $class_name
	 * @return Log
	 */
	public static function model($class_name = __CLASS__){
		return parent::model($class_name);
	}
	
	/**
	 * 记录日志
	 * @param string $code 错误码
	 * @param mixed $data 相关数据(若为数组，会被转为json存储)
	 * @param int $type 错误级别，在Logs中定义错误级别常量
	 */
	public static function set($code, $data, $type = Logs::TYPE_NORMAL){
		Logs::model()->insert(array(
			'code'=>$code,
			'data'=>is_array($data) ? json_encode($data) : $data,
			'type'=>$type,
			'user_id'=>isset(\F::app()->current_user) ? \F::app()->current_user : 0,
			'create_time'=>\F::app()->current_time,
			'create_date'=>date('Y-m-d'),
			'ip_int'=>Request::ip2int(\F::app()->ip),
			'user_agent'=>isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
		));
	}
	
	public static function getType($type){
		switch($type){
			case Logs::TYPE_ERROR:
				return '<span class="fc-red">错误</span>';
			break;
			case Logs::TYPE_NORMAL:
				return '<span>正常</span>';
			break;
			case Logs::TYPE_WARMING:
				return '<span class="fc-orange">警告</span>';
			break;
		}
	}
}