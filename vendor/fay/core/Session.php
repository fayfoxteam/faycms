<?php
namespace fay\core;

class Session{
	private static $_instance;
	
	private function __construct(){}
	
	private function __clone(){}
	
	public static function getInstance(){
		if(!(self::$_instance instanceof self)){
			session_start();
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * 设置session
	 */
	public function set($key, $value){
		$session_namespace = \F::config()->get('session_namespace');
		$_SESSION[$session_namespace][$key] = $value;
		return true;
	}
	
	/**
	 * 获取session
	 */
	public function get($key = false, $default = null, $session_namespace = null){
		$session_namespace || $session_namespace = \F::config()->get('session_namespace');
		if($key === false){
			return $_SESSION[$session_namespace];
		}
		$key_exploded = explode('.', $key);
		$first_key_part = array_shift($key_exploded);
		if(isset($_SESSION[$session_namespace][$first_key_part])){
			$temp = $_SESSION[$session_namespace][$first_key_part];
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
	 * @param string $session_namespace 命名空间
	 */
	public function remove($key = null, $session_namespace = null){
		$session_namespace || $session_namespace = \F::config()->get('session_namespace');
		if($key === null){
			unset($_SESSION[$session_namespace]);
		}else{
			unset($_SESSION[$session_namespace][$key]);
		}
		return true;
	}
	
	/**
	 * 销毁指定命名空间下的所有session
	 * @param null|string $session_namespace 若为null，默认为Config中指定的命名空间
	 */
	public function flush($session_namespace = null){
		$session_namespace || $session_namespace = \F::config()->get('session_namespace');
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