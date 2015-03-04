<?php
namespace fay\core;

use fay\core\Config;

class FBase{
	/**
	 * 获取配置文件配置项
	 * @param String $item
	 */
	public function config($item, $filename = 'main', $mode = 'merge'){
		if($item == '*'){
			return Config::getInstance()->getFile($filename, $mode);
		}else{
			return Config::getInstance()->get($item, $filename, $mode);
		}
	}
	
	/**
	 * 动态配置配置项
	 */
	public function setConfig($item, $value, $filename = 'main'){
		Config::getInstance()->set($item, $value, $filename);
	}	
}