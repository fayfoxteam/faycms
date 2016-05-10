<?php
namespace fay\core;

class Session{
	private static $_instance;
	
	private function __construct(){}
	
	private function __clone(){}
	
	public static function getInstance(){
		if(!(self::$_instance instanceof self)){
			$session_configs = \F::config()->get('session');
			
			foreach($session_configs['ini_set'] as $key => $config){
				if($config !== null){
					ini_set('session.' . $key, $config);
				}
			}
			
			session_start();
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * 设置Session
	 * @param string $key Session名
	 * @param mix $value Session值
	 * @param string $namespace 命名空间，实际上就是数组前缀。若为null，则根据配置文件设置
	 */
	public function set($key, $value, $namespace = null){
		if($namespace === null){
			$namespace = \F::config()->get('session.namespace');
		}
		$_SESSION[$namespace][$key] = $value;
		return true;
	}
	
	/**
	 * 获取Session
	 * @param string $key Session名
	 *   - 若为null，则以键值数组返回所有指定$namespace下的Session，数组key为Session名
	 *   - 若为数组，则以键值数组返回所有指定$namespace下符合条件的Session，数组key为Session名
	 *   - 若为字符串，则返回有指定$namespace下对应的Session值
	 * @param string $default
	 * @param string $namespace 命名空间，实际上就是数组前缀。若为null，则根据配置文件设置
	 */
	public function get($key = null, $default = null, $namespace = null){
		if($namespace === null){
			$namespace = \F::config()->get('session.namespace');
		}
		if($key === null){
			return $_SESSION[$namespace];
		}
		$key_exploded = explode('.', $key);
		$first_key_part = array_shift($key_exploded);
		if(isset($_SESSION[$namespace][$first_key_part])){
			$temp = $_SESSION[$namespace][$first_key_part];
			foreach($key_exploded as $k){
				if(isset($temp[$k])){
					$temp = $temp[$k];
				}else{
					return $default;
				}
			}
			return $temp;
		}else{
			return $default;
		}
	}
	
	/**
	 * 销毁指定命名空间下的session
	 * @param string|null $key 若不指定或者指定为null，则删除所有session
	 * @param string $namespace 命名空间，实际上就是数组前缀。若为null，则根据配置文件设置
	 */
	public function remove($key = null, $namespace = null){
		if($namespace === null){
			$namespace = \F::config()->get('session.namespace');
		}
		if($key === null){
			unset($_SESSION[$namespace]);
		}else{
			unset($_SESSION[$namespace][$key]);
		}
		return true;
	}
	
	/**
	 * 销毁指定命名空间下的所有session
	 * @param null|string $namespace 命名空间，实际上就是数组前缀。若为null，则根据配置文件设置
	 */
	public function flush($namespace = null){
		if($namespace === null){
			$namespace = \F::config()->get('session.namespace');
		}
		$this->remove();
	}
	
	/**
	 * 设置flash消息
	 * @param string $key
	 * @param mixed $message
	 */
	public function setFlash($key, $message){
		$this->set('flash_'.$key, $message);
	}
	
	/**
	 * 获取flash信息（获取的同时会删除该条flash信息）
	 * @param string $key
	 */
	public function getFlash($key){
		if($flash = $this->get('flash_'.$key)){
			$this->remove('flash_'.$key);
			return $flash;
		}else{
			return false;
		}
	}
}