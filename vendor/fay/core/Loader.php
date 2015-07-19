<?php
namespace fay\core;

class Loader{
	/**
	 * 自动加载类库
	 * @param String $class_name 类名
	 */
	public static function autoload($class_name){
		if(strpos($class_name, 'fay') === 0 || strpos($class_name, 'cms') === 0 ){
			$file_path = str_replace('\\', '/', SYSTEM_PATH.$class_name.'.php');
			if(file_exists($file_path)){
				require $file_path;
				return true;
			}
		}else if(strpos($class_name, APPLICATION) === 0){
			$file_path = str_replace('\\', '/', APPLICATION_PATH.substr($class_name, strlen(APPLICATION)).'.php');
			if(file_exists($file_path)){
				require $file_path;
				return true;
			}
		}
	}
	
	/**
	 * 引入一个第三方文件
	 * 本质上是从vendor文件夹包含一个文件进来
	 * @param unknown $name
	 * @throws ErrorException
	 */
	public static function vendor($name){
		if(file_exists(APPLICATION_PATH . "{$name}.php")){
			require_once APPLICATION_PATH . "{$name}.php";
		}else if(file_exists(SYSTEM_PATH . "{$name}.php")){
			require_once SYSTEM_PATH . "{$name}.php";
		}else{
			throw new ErrorException("File '{$name}' not found");
		}
	}
}