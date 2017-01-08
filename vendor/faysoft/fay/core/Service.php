<?php
namespace fay\core;

class Service{
	/**
	 * 获取一个model实例（单例模式）
	 * @param string $class_name
	 * @return mixed
	 */
	public static function service($class_name = __CLASS__){
		return Loader::singleton($class_name);
	}
}