<?php
namespace fay\core;

use fay\helpers\ArrayHelper;
class Config{
	private static $_instance;
	private $_configs;
	
	private function __construct(){}
	
	private function __clone(){}
	
	public static function getInstance(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * 获取配置文件配置项
	 * @param string $item 可以点式操作，例如db.host获取二维数组db下的host
	 * @param string $filename
	 * @return mixed
	 */
	public function get($item, $filename = 'main'){
		if($item == '*'){
			return $this->getFile($filename);
		}
		if(!isset($this->_configs[$filename])){
			$this->getFile($filename);
		}
		$item = explode('.', $item);
		$temp = $this->_configs[$filename];
		foreach($item as $i){
			if(isset($temp[$i])){
				$temp = $temp[$i];
			}else{
				return null;
			}
		}
		return $temp;
	}
	
	/**
	 * 获取配置文件
	 * @param String $filename 配置文件文件名
	 * @return array
	 */
	public function getFile($filename = 'main'){
		if(isset($this->_configs[$filename])){
			return $this->_configs[$filename];
		}
		
		$config = array();
		if(file_exists(APPLICATION_PATH . 'configs/' . $filename . '.php')){
			$config = require APPLICATION_PATH . 'configs/' . $filename . '.php';
		}
		
		if(file_exists(CMS_PATH . 'configs/' . $filename . '.php')){
			$config = ArrayHelper::merge(require CMS_PATH . 'configs/' . $filename . '.php', $config);
		}
		
		return $this->_configs[$filename] = $config;
	}
	
	/**
	 * 运行中动态配置配置项
	 * @param string $item 可以点式操作，例如db.host修改二维数组db下的host
	 * @param mixed $value
	 * @param null|string $filename
	 */
	public function set($item, $value, $filename = null){
		$filename || $filename = 'main';
		if (!isset($this->_configs[$filename])){
			$this->getFile($filename);
		}
		if(strpos($item, '.') !== false){
			$item = explode('.', $item);
			eval('$this->_configs[$filename][\''.implode('\'][\'', $item).'\'] = $value;');
		}else{
			$this->_configs[$filename][$item] = $value;
		}
	}
}