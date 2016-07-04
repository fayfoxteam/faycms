<?php
namespace fay\core;

class Http{
	/**
	 * 从$_SERVER数组中获取一个值
	 * @param $key
	 * @param null $default
	 * @return mixed
	 */
	public function getServer($key, $default = null){
		if(null === $key){
			return $_SERVER;
		}
		
		return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
	}
	
	/**
	 * 获取请求方式（GET, POST等）
	 * @return string
	 */
	public function getMethod(){
		return $this->getServer('REQUEST_METHOD');
	}
	
	/**
	 * 判断是不是POST请求
	 * @return bool
	 */
	public function isPost(){
		return $this->getMethod() == 'POST';
	}
	
	/**
	 * 判断是不是GET请求
	 * @return bool
	 */
	public function isGet(){
		return $this->getMethod() == 'GET';
	}
	
	
	
	/**
	 * 判断是否为ajax访问
	 */
	public static function isAjax(){
		if(\F::input()->request('ajax')){
			return true;
		}else{
			if((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') ||
				isset($_SERVER['HTTP_POSTMAN_TOKEN'])//postman发起的请求视为ajax请求
			){
				return true;
			}else{
				return false;
			}
		}
	}
}