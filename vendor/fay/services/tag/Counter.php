<?php
namespace fay\services\tag;

use fay\core\Service;
use fay\helpers\FieldHelper;
use fay\models\tables\TagCounter;
use fay\models\tables\UserCounter;

class Counter extends Service{
	/**
	 * 可返回字段
	 */
	public static $public_fields = array(
		'posts', 'feeds'
	);
	
	/**
	 * @param string $class_name
	 * @return Counter
	 */
	public static function service($class_name = __CLASS__){
		return parent::service($class_name);
	}
	
	/**
	 * 递增一个或多个指定标签的计数
	 * @param array|int $tag_ids
	 * @param string $field tag_counter表对应的列名
	 * @param int $value 增量，默认为1，可以是负数
	 * @return int
	 */
	public function incr($tag_ids, $field, $value = 1){
		if(!$tag_ids){
			return 0;
		}
		if(!is_array($tag_ids)){
			$tag_ids = explode(',', $tag_ids);
		}
		
		return TagCounter::model()->incr(array(
			'tag_id IN (?)'=>$tag_ids,
		), $field, $value);
	}
	
	/**
	 * 递减一个或多个指定标签的计数
	 * @param array|int $tag_ids
	 * @param string $field tag_counter表对应的列名
	 * @param int $value 增量，默认为1，正数表示递减
	 * @return int
	 */
	public function decr($tag_ids, $field, $value = 1){
		return $this->incr($tag_ids, $field, -$value);
	}
	
	/**
	 * 获取标签信息
	 * @param int $user_id 标签ID
	 * @param string $fields 附件字段（user_profile表字段）
	 * @return array 返回包含标签profile信息的二维数组
	 */
	public function get($user_id, $fields = null){
		//若传入$fields为空，则返回默认字段
		$fields || $fields = self::$public_fields;
		
		//格式化fields
		$fields = FieldHelper::parse($fields, null, self::$public_fields);
		
		return UserCounter::model()->fetchRow(array(
			'user_id = ?'=>$user_id,
		), $fields['fields']);
	}
	
	/**
	 * 批量获取标签信息
	 * @param array $user_ids 标签ID一维数组
	 * @param string $fields 附件字段（user_profile表字段）
	 * @return array 返回以标签ID为key的三维数组
	 */
	public function mget($user_ids, $fields = null){
		//若传入$fields为空，则返回默认字段
		$fields || $fields = self::$public_fields;
		
		//格式化fields
		$fields = FieldHelper::parse($fields, null, self::$public_fields);
		
		if(!in_array('user_id', $fields['fields'])){
			$fields['fields'][] = 'user_id';
			$remove_user_id = true;
		}else{
			$remove_user_id = false;
		}
		$profiles = UserCounter::model()->fetchAll(array(
			'user_id IN (?)'=>$user_ids,
		), $fields['fields'], 'user_id');
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