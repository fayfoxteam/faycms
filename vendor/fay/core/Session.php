<?php
namespace fay\core;

use fay\core\FBase;

class Session extends FBase{
	private static $_instance;
	
	private function __construct(){}
	
	private function __clone(){}
	
	public static function getInstance(){
		if(!(self::$_instance instanceof self)){
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
		if(isset($_SESSION[$session_namespace][$key])){
			return $_SESSION[$session_namespace][$key];
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
	 */
	public function flush($session_namespace = null){
		$session_namespace || $session_namespace = \F::config()->get('session_namespace');
		$this->remove();
	}
	
	/**
	 * 设置flash消息
	 * @param string $key
	 * @param mix $message
	 */
	public function setFlash($key, $message){
		$this->set('flash_'.$key, $message);
	}
	
	/**
	 * 获取flash信息
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