<?php
namespace fay\core;

class Cache{
	private static $_instance;
	
	public static $map = array(
		'file'=>'fay\caching\File',
		'memcache'=>'fay\caching\Memcache',
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
	 * 一次性获取多个缓存
	 * @param array $keys，一维数组的方式传入多个key
	 * @param string $driver 缓存驱动，若为null，则默认为main.php中配置的缓存方式
	 * @throws \fay\core\ErrorException
	 */
	public function mget($keys, $driver = null){
		$driver || $driver = \F::config()->get('default_cache_driver');
		
		if(empty($driver)){
			return null;
		}else if(!isset(self::$map[$driver])){
			throw new ErrorException("{$driver} 缓存方式未注册");
		}
		
		if(!in_array($driver, self::$drivers)){
			self::$drivers[$driver] = new self::$map[$driver];
		}
		
		return self::$drivers[$driver]->mget($keys);
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
	 * 设置多个缓存
	 * @param mix $data
	 * @param int $duration 缓存过期时间（单位：秒）
	 * @param string $driver 缓存驱动，若为null，则默认为main.php中配置的缓存方式
	 * @throws \fay\core\ErrorException
	 * @return boolean
	 */
	public function mset($data, $duration = 0, $driver = null){
		$driver || $driver = \F::config()->get('default_cache_driver');
		
		if(empty($driver)){
			return false;
		}else if(!isset(self::$map[$driver])){
			throw new ErrorException("{$driver} 缓存方式未注册");
		}
		
		if(!in_array($driver, self::$drivers)){
			self::$drivers[$driver] = new self::$map[$driver];
		}
		
		return self::$drivers[$driver]->mset($data, $duration);
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
}