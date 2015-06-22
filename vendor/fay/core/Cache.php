<?php
namespace fay\core;

class Cache{
	private static $_instance;
	
	public static $map = array(
		'file'=>'fay\caching\File',
	);
	
	public static $drivers = array();
	
	public static function getInstance(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * 获取缓存
	 * @param mix $key
	 * @param string $driver 缓存驱动，若为null，则默认为main.php中配置的缓存方式
	 * @throws \fay\core\ErrorException
	 */
	public function get($key, $driver = null){
		$driver || $driver = \F::config()->get('default_cache_driver');
		
		if(empty($driver)){
			return null;
		}else if(!isset(self::$map[$driver])){
			throw new ErrorException("{$driver} 缓存方式未注册");
		}
		
		if(!in_array($driver, self::$drivers)){
			self::$drivers[$driver] = new self::$map[$driver];
		}
		
		return self::$drivers[$driver]->get($key);
	}
	
	/**
	 * 设置缓存
	 * @param mix $key
	 * @param mix $value
	 * @param int $duration 缓存过期时间（单位：秒）
	 * @param string $driver 缓存驱动，若为null，则默认为main.php中配置的缓存方式
	 * @throws \fay\core\ErrorException
	 * @return boolean
	 */
	public function set($key, $value, $duration = 0, $driver = null){
		$driver || $driver = \F::config()->get('default_cache_driver');
		
		if(empty($driver)){
			return false;
		}else if(!isset(self::$map[$driver])){
			throw new ErrorException("{$driver} 缓存方式未注册");
		}
		
		if(!in_array($driver, self::$drivers)){
			self::$drivers[$driver] = new self::$map[$driver];
		}
		
		return self::$drivers[$driver]->set($key, $value, $duration);
	}
	
	/**
	 * 删除一个缓存
	 * @param mix $key
	 * @param $prefix 如果缓存机制支持，且prefix不为null，可以删除key以prefix开头的缓存
	 * @throws \fay\core\ErrorException
	 * @return bool
	 */
	public function delete($key, $driver = null){
		$driver || $driver = \F::config()->get('default_cache_driver');
		
		if(empty($driver)){
			return false;
		}else if(!isset(self::$map[$driver])){
			throw new ErrorException("{$driver} 缓存方式未注册");
		}
		
		if(!in_array($driver, self::$drivers)){
			self::$drivers[$driver] = new self::$map[$driver];
		}
		
		return self::$drivers[$driver]->delete($key);
	}
	
	/**
	 * 清空缓存
	 * @param string $prefix
	 * @param $prefix 如果缓存机制支持，且prefix不为null，可以删除key以prefix开头的缓存
	 * @throws \fay\core\ErrorException
	 * @return bool
	 */
	public function flush($prefix, $driver = null){
		$driver || $driver = \F::config()->get('default_cache_driver');
		
		if(empty($driver)){
			return false;
		}else if(!isset(self::$map[$driver])){
			throw new ErrorException("{$driver} 缓存方式未注册");
		}
		
		if(!in_array($driver, self::$drivers)){
			self::$drivers[$driver] = new self::$map[$driver];
		}
		
		return self::$drivers[$driver]->flush($prefix);
	}
	
	/**
	 * 注册一个缓存方式
	 * @param string $name
	 * @param string $class_name 带命名空间的类名
	 */
	public function registerDriver($name, $class_name){
		self::$map[$name] = $class_name;
	}
	
	/**
	 * 获取一个缓存实例
	 * @param string $driver 缓存方式
	 * @throws \fay\core\ErrorException
	 * @return \fay\caching\Cache
	 */
	public function getDriver($driver){
		if(empty($driver)){
			return false;
		}else if(!isset(self::$map[$driver])){
			throw new ErrorException("{$driver} 缓存方式未注册");
		}
		
		if(!in_array($driver, self::$drivers)){
			self::$drivers[$driver] = new self::$map[$driver];
		}
		return self::$drivers[$driver];
	}
	
	
	
	
	
	
// 	private static $_instance;
// 	private $_memcache = null;
	
// 	private $_memcache_config = array();
	
// 	public function __construct(){
// 		$this->_memcache_config = \F::config()->get('memcache', 'memcache');
// 		self::$_instance = $this;
// 	}
	
// 	public static function getInstance(){
// 		if(!(self::$_instance instanceof self)){
// 			self::$_instance = new self();
// 		}
// 		return self::$_instance;
// 	}
	
// 	/**
// 	 * @return Memcache
// 	 */
// 	public function memcache(){
// 		if(!class_exists('Memcache', false)){
// 			return false;
// 		}
// 		if(!$this->_memcache){
// 			$this->_memcache = new \Memcache();
// 			$this->_memcache->connect($this->_memcache_config['host'], $this->_memcache_config['port']);
// 		}
// 		return $this->_memcache;
// 	}
	
// 	public function set($key, $value, $expire = 0, $mode = 'memcache', $flag = null){
// 		if($mode == 'memcache'){
// 			return $this->_memcache_set($key, $value, $expire, $flag);
// 		}
// 	}
	
// 	public function get($key, $mode = 'memcache'){
// 		if($mode == 'memcache'){
// 			return $this->_memcache_get($key);
// 		}
// 	}
	
// 	public function delete($key, $mode = 'memcache'){
// 		if($mode == 'memcache'){
// 			return $this->_memcache_delete($key);
// 		}
// 	}
	
// 	public function flush($mode = 'memcache'){
// 		if($mode == 'memcache'){
// 			return $this->_memcache_flush();
// 		}
// 	}
	
// 	private function _memcache_set($key, $value, $expire = 0, $flag = null){
// 		$expire || $expire = $this->_memcache_config['expire'];
// 		$flag || $flag = $this->_memcache_config['flag'];
// 		return $this->memcache()->set($key, $value, $flag, $expire);
// 	}
	
// 	private function _memcache_get($key){
// 		return $this->memcache()->get($key);
// 	}
	
// 	private function _memcache_delete($key){
// 		return $this->memcache()->delete($key);
// 	}
	
// 	private function _memcache_flush(){
// 		$this->memcache()->flush();
// 	}
}