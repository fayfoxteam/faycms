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
	 * @param string $name 事件
	 * @param callable $handler 回调函数
	 * @param string $router 正则，若非空，则仅匹配的路由会执行事件
	 * @param array $data 可以在绑定事件时传入一些参数
	 * @param bool $append 若为true，则追加到最后，若为false，则插入在最前
	 */
	public function on($name, $handler, $router = '', $data = array(), $append = true){
		if($append || empty(self::$events[$name])){
			self::$events[$name][] = array(
				'handler'=>$handler,
				'router'=>$router,
				'data'=>$data,
			);
		}else{
			array_unshift(self::$events[$name], array(
				'handler'=>$handler,
				'router'=>$router,
				'data'=>$data,
			));
		}
	}
	
	/**
	 * 触发事件
	 * @param string $name 事件
	 * @param array $data 参数
	 */
	public function trigger($name, $data = array()){
		if(isset(self::$events[$name])){
			foreach(self::$events[$name] as $e){
				if(!empty($e['router']) && !preg_match($e['router'], Uri::getInstance()->router)){
					//设置有路由正则，且正则不匹配当前路由，跳过
					continue;
				}
				
				call_user_func($e['handler'], array_merge($e['data'], $data));
			}
		}
	}
}