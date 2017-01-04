<?php
namespace fay\core;

class Event{
	private static $events = array();
	
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
		self::$events = \F::config()->get('*', 'events');
	}
	
	/**
	 * 绑定事件
	 * @param string $event 事件
	 * @param callable $handler 回调函数
	 * @param string $router 正则，若非空，则仅匹配的路由会执行事件
	 */
	public function on($event, $handler, $router = ''){
		self::$events[$event][] = array(
			'handler'=>$handler,
			'router'=>$router,
		);
	}
	
	/**
	 * 触发事件
	 * @param string $event 事件
	 * @param array $data 参数
	 */
	public function trigger($event, $data = array()){
		if(isset(self::$events[$event])){
			foreach(self::$events[$event] as $e){
				if(!empty($e['router']) && !preg_match($e['router'], Uri::getInstance()->router)){
					//设置有路由正则，且正则不匹配当前路由，跳过
					continue;
				}
				
				call_user_func($e['handler'], $data);
			}
		}
	}
}