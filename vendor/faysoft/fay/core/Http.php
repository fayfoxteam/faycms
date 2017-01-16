<?php
namespace fay\core;

class Http{
	/**
	 * 从$_SERVER数组中获取一个值
	 * @param $key
	 * @param null $default
	 * @return mixed
	 */
	public static function getServer($key, $default = null){
		if(null === $key){
			return $_SERVER;
		}
		
		return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
	}
	
	/**
	 * 获取请求方式（GET, POST等）
	 * @return string
	 */
	public static function getMethod(){
		return static::getServer('REQUEST_METHOD');
	}
	
	/**
	 * 判断是不是POST请求
	 * @return bool
	 */
	public static function isPost(){
		return static::getMethod() == 'POST';
	}
	
	/**
	 * 判断是不是GET请求
	 * @return bool
	 */
	public static function isGet(){
		return static::getMethod() == 'GET';
	}
	
	/**
	 * 获取输入流
	 * @return bool|string
	 */
	public static function getRawBody(){
		$body = file_get_contents('php://input');
		
		return strlen(trim($body)) > 0 ? $body : false;
	}
	
	/**
	 * 判断是否为ajax访问
	 */
	public static function isAjax(){
		if(\F::input()->request('ajax')){
			return true;
		}else{
			if(static::getServer('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest' ||
				static::getServer('HTTP_POSTMAN_TOKEN')//postman发起的请求视为ajax请求
			){
				return true;
			}else{
				return false;
			}
		}
	}
	
	/**
	 * 是否https访问
	 * @return boolean
	 */
	public static function isSecure(){
		return static::getScheme() === 'https';
	}
	
	/**
	 * 获取请求协议
	 * @return string
	 */
	public static function getScheme(){
		return static::getServer('HTTPS') == 'on' ? 'https' : 'http';
	}
	
	/**
	 * 猜测$base_url
	 * @return string
	 */
	public static function getBaseUrl(){
		$document_root = $_SERVER['DOCUMENT_ROOT'];
		$document_root = trim($document_root, '\\/');//由于服务器配置不同，有的DOCUMENT_ROOT末尾带斜杠，有的不带，这里统一去掉末尾斜杠
		$folder = dirname(str_replace($document_root, '', $_SERVER['SCRIPT_FILENAME']));
		//所有斜杠都以正斜杠为准
		$folder = str_replace('\\', '/', $folder);
		if(substr($folder, -7) == '/public'){
			$folder = substr($folder, 0, -7);
		}
		if($folder == '/'){
			//仅剩一根斜杠的时候（把根目录设到public目录下的情况），设为空
			$folder = '';
		}
		$base_url = self::getScheme() .
			'://' .
			(isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']) .
			$folder .
			'/';
		if(defined('NO_REWRITE')){
			$base_url .= 'index.php/';
		}
		
		return $base_url;
	}
	
	/**
	 * 获取当前请求的url
	 * @return string
	 */
	public static function getCurrentUrl(){
		return self::getScheme() . '://'.(isset($_SERVER['HTTP_X_FORWARDED_HOST']) ?: $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI'];
	}
}