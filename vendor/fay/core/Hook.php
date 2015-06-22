<?php
namespace fay\core;

use fay\core\FBase;

class Hook extends FBase{
	private $hooks = array();
	
	private static $_instance;
	private function __construct(){}
	
	private function __clone(){}
	
	public static function getInstance(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self();
			
			self::$_instance->init();
		}
		return self::$_instance;
	}
	
	private function init(){
		$this->hooks = \F::config()->get('*', 'hooks');
	}
	
	/**
	 * 在锚点位置调用hook
	 * @param string $key 锚点位置
	 * @param array $params 参数，调用的时候传入
	 */
	public function call($key, $params = array()){
		if(isset($this->hooks[$key])){
			foreach($this->hooks[$key] as $hook){
				//设置有路由正则，且正则不匹配当前路由，跳过
				if(isset($hook['router']) && !preg_match($hook['router'], Uri::getInstance()->router)){
					continue;
				}
				
				$this->run($hook, $params);
			}
		}
	}
	
	/**
	 * 执行一个具体的hook
	 * @param array $config 配置信息
	 * @param array $params 调用的时候可能会传入参数
	 */
	public function run($config, $params = array()){
		if(is_array($config['function'])){
			$hook = new $config['function'][0];
			$hook->{$config['function'][1]}($params);
		}else if($config['function'] instanceof \Closure){
			$config['function']($params);
		}else{
			call_user_func($config['function'], $params);
		}
	}
}