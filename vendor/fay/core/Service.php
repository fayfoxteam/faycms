<?php
namespace fay\core;

class Service{
	private static $_services = array();
	
	/**
	 * 获取一个model实例（单例模式）
	 * @param string $class_name
	 * @return mixed
	 */
	public static function service($class_name = __CLASS__){
		if(isset(self::$_services[$class_name])){
			return self::$_services[$class_name];
		}else{
			return self::$_services[$class_name] = new $class_name();
		}
	}
}