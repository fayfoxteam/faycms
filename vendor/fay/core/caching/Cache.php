<?php
namespace fay\core\caching;

abstract class Cache{
	/**
	 * 缓存键前缀
	 */
	public $key_prefix;
	
	/**
	 * 序列化、反序列化函数
	 */
	public $serializer = array('json_encode', 'json_decode');
	
	/**
	 * 作为缓存$key的分隔符。
	 * File是"/"斜杠
	 * Memcache是"."点号
	 * Redis是":"冒号
	 */
	public $separator = '.';
	
	public function __construct(){
		if($this->key_prefix === null){
			$this->key_prefix = APPLICATION;
		}
	}
	
	/**
	 * 返回一个key
	 * 若key_prefix不为空，则前缀上key_prefix.separator
	 * @param mix $key
	 * @return string
	 */
	protected function buildKey($key){
		if(is_string($key)){
			$key = implode($this->separator, preg_split('/[\/\.:]/', $key));
		}else{
			$key = md5(json_encode($key));
		}
		
		return $this->key_prefix ? $this->key_prefix . $this->separator . $key : $key;
	}
	
	/**
	 * 设置一个缓存
	 * @param mix $key $key中若包含斜杠（/），点号（.），冒号（:）会被统一转为$this->separator
	 *   即仅这3个符号存在差别的key会被视为同一个key
	 * @param mix $value
	 * @param int $duration 缓存过期时间（单位：秒）
	 */
	public function set($key, $value, $duration = 0){
		if(!empty($this->serializer[0])){
			$value = $this->serializer[0]($value);
		}
		
		$key = $this->buildKey($key);
		
		return $this->setValue($key, $value, $duration);
	}
	
	/**
	 * 设置多个缓存
	 * @param array $items
	 * @param int $duration 缓存过期时间（单位：秒）
	 */
	public function mset($items, $duration = 0){
		$data = array();
		foreach ($items as $key => $value) {
			$key = $this->buildKey($key);
			if(!empty($this->serializer[0])){
				$data[$key] = $this->serializer[1]($value);
			}else{
				$data[$key] = $value;
			}
		}
		
		return $this->setValues($data, $duration);
	}
	
	/**
	 * 根据指定key从缓存中获取一个元素
	 * @param mix $key
	 * @return mix
	 */
	public function get($key){
		$key = $this->buildKey($key);
		$value = $this->getValue($key);
		if($value === null || empty($this->serializer[1])){
			return $value;
		}else if(!empty($this->serializer[1])){
			return $this->serializer[1]($value);
		}
	}
	
	/**
	 * 一次性获取多个缓存
	 * @param array $keys，一维数组的方式传入多个key
	 * @return 以传入$keys为键的数组，若某个缓存项不存在，则对应null
	 */
	public function mget($keys){
		$key_map = array();
		foreach ($keys as $key) {
			$key_map[$key] = $this->buildKey($key);
		}
		$values = $this->getValues(array_values($key_map));
		$results = array();
		foreach ($key_map as $key => $new_key) {
			if(isset($values[$new_key])){
				if(!empty($this->serializer[1])){
					$results[$key] = $this->serializer[1]($values[$new_key]);
				}else{
					$results[$key] = $values[$new_key];
				}
			}else{
				$results[$key] = null;
			}
		}
		
		return $results;
	}
	
	/**
	 * 删除一个缓存
	 * @param mix $key
	 */
	public function delete($key){
		$key = $this->buildKey($key);
		
		return $this->deleteValue($key);
	}
	
	/**
	 * 判断缓存中某个key是否存在
	 * @param mix $key
	 * @return boolean
	 */
	public function exists($key){
		$key = $this->buildKey($key);
		$value = $this->getValue($key);
		
		return $value !== false;
	}
	
	/**
	 * 清空部分或全部缓存
	 * @param $prefix 如果缓存机制支持，且prefix不为null，可以删除key以prefix开头的缓存
	 */
	public function flush($prefix = null){
		return $this->flushValues($prefix);
	}
	
	/**
	 * 设置单个缓存
	 * @param mix $key
	 * @param mix $value
	 * @param int $duration 缓存过期时间（单位：秒）
	 */
	abstract protected function setValue($key, $value, $duration);
	
	/**
	 * 获取单个缓存
	 * @param mix $key
	 */
	abstract protected function getValue($key);
	
	/**
	 * 获取多个缓存值，若缓存机制允许一次性获取多个值，请重写此方法
	 * @param array $keys
	 */
	protected function getValues($keys){
		$results = array();
		foreach ($keys as $key) {
			$results[$key] = $this->getValue($key);
		}
	
		return $results;
	}
	
	/**
	 * 删除单个缓存
	 * @param mix $key
	 */
	abstract protected function deleteValue($key);
	
	/**
	 * 清空部分或全部缓存
	 * @param $prefix 如果缓存机制支持，且prefix不为null，可以删除key以prefix开头的缓存
	 */
	abstract protected function flushValues($prefix = null);
}