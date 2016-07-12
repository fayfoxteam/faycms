<?php
namespace fay\services\user;

use fay\core\Service;
use fay\models\tables\UserCounter;

class Counter extends Service{
	/**
	 * @param string $class_name
	 * @return Counter
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 递增一个或多个指定用户的计数
	 * @param array|int $user_ids
	 * @param string $field user_counter表对应的列名
	 * @param int $value 增量，默认为1，可以是负数
	 * @return int
	 */
	public function incr($user_ids, $field, $value = 1){
		if(!$user_ids){
			return 0;
		}
		if(!is_array($user_ids)){
			$user_ids = explode(',', $user_ids);
		}
		
		return UserCounter::model()->incr(array(
			'user_id IN (?)'=>$user_ids,
		), $field, $value);
	}
	
	/**
	 * 递减一个或多个指定用户的计数
	 * @param array|int $user_ids
	 * @param string $field user_counter表对应的列名
	 * @param int $value 增量，默认为-1，可以是正数
	 * @return int
	 */
	public function decr($user_ids, $field, $value = -1){
		return $this->incr($user_ids, $field, $value);
	}
}