<?php
namespace fay\caching;

/**
 * 数组缓存，仅在一次请求内有效
 */
class ArrayCache extends Cache{
	/**
	 * 作为缓存$key的分隔符。
	 */
	public $separator = '_';
	
	/**
	 * 缓存
	 */
	private $_cache;
	
	/**
	 * 判断键是否存在
	 * @param string $key
	 * @return bool
	 */
	public function exists($key){
		$key = $this->buildKey($key);
		return isset($this->_cache[$key]) && ($this->_cache[$key][1] === 0 || $this->_cache[$key][1] > microtime(true));
	}
	
	/**
	 * @see \fay\caching\Cache::getValue()
	 * @param string $key
	 * @return mixed
	 */
	protected function getValue($key){
		if (isset($this->_cache[$key]) && ($this->_cache[$key][1] === 0 || $this->_cache[$key][1] > microtime(true))) {
			return $this->_cache[$key][0];
		} else {
			return null;
		}
	}
	
	/**
	 * @see \fay\caching\Cache::setValue()
	 * @param string $key
	 * @param mixed $value
	 * @param float $duration 由于一次请求时间本来就很短，所以过期时间支持毫秒级过期时间
	 * @return bool
	 */
	protected function setValue($key, $value, $duration){
		$this->_cache[$key] = array($value, $duration === 0 ? 0 : microtime(true) + $duration);
		return true;
	}
	
	/**
	 * @see \fay\caching\Cache::deleteValue()
	 * @param string $key
	 * @return bool
	 */
	protected function deleteValue($key){
		unset($this->_cache[$key]);
		return true;
	}
	
	/**
	 * @see \fay\caching\Cache::flushValues()
	 * @param string $prefix 由于数组缓存成本很低，没必要弄太复杂，无视$prefix参数
	 * @return bool
	 */
	protected function flushValues($prefix = null){
		$this->_cache = array();
		return true;
	}
}
