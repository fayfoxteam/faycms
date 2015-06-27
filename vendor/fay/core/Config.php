<?php
namespace fay\core;

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
	 * @param string $mode 仅第一次读取配置文件时，此参数有效
	 */
	public function get($item, $filename = 'main', $mode = 'merge'){
		if($item == '*'){
			return $this->getFile($filename, $mode);
		}
		if(!isset($this->_configs[$filename])){
			$this->getFile($filename, $mode);
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
	 * @param String $mode 配置文件获取模式<br>
	 *   overwrite 不获取系统本身的配置文件<br>
	 *   merge 用array_merge函数进行合并<br>
	 *   merge_recursive 用array_merge_recursive函数进行合并
	 */
	public function getFile($filename = 'main', $mode = 'merge'){
		if(isset($this->_configs[$filename])){
			return $this->_configs[$filename];
		}
		
		$config = array();
		if(file_exists(APPLICATION_PATH . 'configs/' . $filename . '.php')){
			$config = require APPLICATION_PATH . 'configs/' . $filename . '.php';
		}
		
		if($mode != 'overwrite' && file_exists(BASEPATH . '../configs/' . $filename . '.php')){
			if($mode == 'merge'){
				$config = array_merge(require BASEPATH . '../configs/' . $filename . '.php', $config);
			}else if($mode == 'merge_recursive'){
				$config = array_merge_recursive(require BASEPATH . '../configs/' . $filename . '.php', $config);
			}
		}
		
		return $this->_configs[$filename] = $config;
	}
	
	/**
	 * 运行中动态配置配置项
	 * @param string $item 可以点式操作，例如db.host修改二维数组db下的host
	 * @param mixed $value
	 * @param string $filename
	 */
	public function set($item, $value, $filename = false){
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