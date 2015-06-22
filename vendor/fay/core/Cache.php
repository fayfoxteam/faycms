<?php
namespace fay\core;

use fay\core\FBase;

class Cache extends FBase{
	private static $_instance;
	private $_memcache = null;
	
	private $_memcache_config = array();
	
	public function __construct(){
		$this->_memcache_config = \F::config()->get('memcache', 'memcache');
		self::$_instance = $this;
	}
	
	public static function getInstance(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * @return Memcache
	 */
	public function memcache(){
		if(!class_exists('Memcache', false)){
			return false;
		}
		if(!$this->_memcache){
			$this->_memcache = new \Memcache();
			$this->_memcache->connect($this->_memcache_config['host'], $this->_memcache_config['port']);
		}
		return $this->_memcache;
	}
	
	public function set($key, $value, $expire = 0, $mode = 'memcache', $flag = null){
		if($mode == 'memcache'){
			return $this->_memcache_set($key, $value, $expire, $flag);
		}
	}
	
	public function get($key, $mode = 'memcache'){
		if($mode == 'memcache'){
			return $this->_memcache_get($key);
		}
	}
	
	public function delete($key, $mode = 'memcache'){
		if($mode == 'memcache'){
			return $this->_memcache_delete($key);
		}
	}
	
	public function flush($mode = 'memcache'){
		if($mode == 'memcache'){
			return $this->_memcache_flush();
		}
	}
	
	private function _memcache_set($key, $value, $expire = 0, $flag = null){
		$expire || $expire = $this->_memcache_config['expire'];
		$flag || $flag = $this->_memcache_config['flag'];
		return $this->memcache()->set($key, $value, $flag, $expire);
	}
	
	private function _memcache_get($key){
		return $this->memcache()->get($key);
	}
	
	private function _memcache_delete($key){
		return $this->memcache()->delete($key);
	}
	
	private function _memcache_flush(){
		$this->memcache()->flush();
	}
}