<?php
namespace fay\caching;

use fay\core\ErrorException;
/**
 * Memcache缓存
 */
class Memcache extends Cache{
	/**
	 * 是否使用Memcached扩展
	 */
	public $use_memcached = false;
	
	/**
	 * 仅use_memcached为true时有效
	 * 保持一个长连接
	 */
	public $persistent_id;
	
	/**
	 * 仅use_memcached为true时有效
	 */
	public $options;
	
	/**
	 * 仅use_memcached为true时有效
	 */
	public $username;
	
	/**
	 * 仅use_memcached为true时有效
	 */
	public $password;
	
	/**
	 * @var \Memcache|\Memcached Memcache实例
	 */
	public $_cache;
	
	/**
	 * memcache服务器列表
	 */
	public $_servers = array();
	
	public function __construct(){
		parent::__construct();
		$this->setServers(\F::config()->getFile('memcache'));
		$this->addServers($this->getMemcache(), $this->getServers());
	}
	
	/**
	 * @param \Memcache|\Memcached $cache
	 * @param array $servers
	 * @throws InvalidConfigException
	 */
	protected function addServers($cache, $servers)
	{
		if(empty($servers)){
			$servers = array(
				'host'=>'127.0.0.1',
				'port'=>11211,
			);
		}else{
			foreach($servers as $server){
				if($server['host'] === null){
					throw new ErrorException('Memcache服务器参数必须指定host');
				}
			}
		}
		if($this->use_memcached){
			$this->addMemcachedServers($cache, $servers);
		} else {
			$this->addMemcacheServers($cache, $servers);
		}
	}
	
	/**
	 * @param \Memcached $cache
	 * @param array $servers
	 */
	protected function addMemcachedServers($cache, $servers){
		$existing_servers = array();
		if($this->persistent_id !== null){
			foreach($cache->getServerList() as $s){
				$existing_servers[$s['host'] . ':' . $s['port']] = true;
			}
		}
		foreach($servers as $server){
			if (empty($existing_servers) || !isset($existing_servers[$server['host'] . ':' . $server['port']])) {
				$cache->addServer($server['host'], $server['port'], $server['weight']);
			}
		}
	}
	
	/**
	 * @param \Memcache $cache
	 * @param array $servers
	 */
	protected function addMemcacheServers($cache, $servers){
		$class = new \ReflectionClass($cache);
		$param_count = $class->getMethod('addServer')->getNumberOfParameters();
		$default_server_config = array(
			'port'=>11211,
			'weight'=>1,
			'persistent'=>true,
			'timeout'=>1,
			'retryInterval'=>15,
			'status'=>true,
			'failureCallback'=>null,
		);
		foreach($servers as $server) {
			$server = $server + $default_server_config;
			if($param_count === 9){
				$cache->addServer(
					$server['host'],
					$server['port'],
					$server['persistent'],
					$server['weight'],
					$server['timeout'],
					$server['retryInterval'],
					$server['status'],
					$server['failureCallback'],
					$server['timeout']
				);
			}else{
				$cache->addServer(
					$server['host'],
					$server['port'],
					$server['persistent'],
					$server['weight'],
					$server['timeout'],
					$server['retryInterval'],
					$server['status'],
					$server['failureCallback']
				);
			}
		}
	}
	
	/**
	 * 获取Memcache实例
	 * @return \Memcache|\Memcached
	 */
	public function getMemcache(){
		if ($this->_cache === null) {
			$extension = $this->use_memcached ? 'memcached' : 'memcache';
			if (!extension_loaded($extension)) {
				throw new ErrorException("PHP {$extension} 扩展未安装.");
			}
			
			if ($this->use_memcached) {
				$this->_cache = $this->persistentId !== null ? new \Memcached($this->persistent_id) : new \Memcached;
				if ($this->username !== null || $this->password !== null) {
					$this->_cache->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
					$this->_cache->setSaslAuthData($this->username, $this->password);
				}
				if (!empty($this->options)) {
					$this->_cache->setOptions($this->options);
				}
			} else {
				$this->_cache = new \Memcache;
			}
		}
		
		return $this->_cache;
	}
	
	/**
	 * 获取Memcache服务器列表
	 */
	public function getServers(){
		return $this->_servers;
	}
	
	/**
	 * @param array $config 必须是二维数组
	 */
	public function setServers($config){
		$this->_servers = $config;
	}
	
	/**
	 * @see \fay\caching\Cache::getValue()
	 */
	protected function getValue($key){
		return $this->_cache->get($key);
	}
	
	/**
	 * @see \fay\caching\Cache::getValues()
	 */
	protected function getValues($keys){
		return $this->use_memcached ? $this->_cache->getMulti($keys) : $this->_cache->get($keys);
	}
	
	/**
	 * @see \fay\caching\Cache::setValue()
	 */
	protected function setValue($key, $value, $duration){
		$expire = $duration > 0 ? $duration + \F::app()->current_time : 0;
		
		return $this->use_memcached ? $this->_cache->set($key, $value, $expire) : $this->_cache->set($key, $value, 0, $expire);
	}
	
	/**
	 * @see \fay\caching\Cache::setValues()
	 */
	protected function setValues($data, $duration){
		if($this->use_memcached){
			$this->_cache->setMulti($data, $duration > 0 ? $duration + time() : 0);
			
			return array();
		}else{
			return parent::setValues($data, $duration);
		}
	}
	
	/**
	 * @see \fay\caching\Cache::deleteValue()
	 */
	protected function deleteValue($key){
		return $this->_cache->delete($key, 0);
	}
	
	/**
	 * memcache的方式不支持根据key前缀删除部分缓存，$prefix不会起作用
	 * @see \fay\caching\Cache::flushValues()
	 */
	protected function flushValues($prefix = null){
		return $this->_cache->flush();
	}
}