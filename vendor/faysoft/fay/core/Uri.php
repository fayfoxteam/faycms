<?php
namespace fay\core;

use fay\helpers\StringHelper;

/**
 * 对url进行路由解析
 * @author karma
 *
 */
class Uri{
	public $router;
	public $module;
	
	/**
	 * 与$_SERVER['REQUEST_URI']相比，如果有二级或多级目录，会去掉目录
	 */
	public $request_uri;
	
	/**
	 * 出于SEO考虑，有些router带有中横线，将其转换为大小写分割，并且首字母大写
	 */
	public $controller;
	
	/**
	 * 出于SEO考虑，有些router带有中横线，将其转换为大小写分割，并且首字母小写
	 */
 	public $action;
	
	/**
	 * 保持中横线分割
	 */
 	public $package = 'cms';
 	
	private static $_instance;
	
	public function __construct(){
		$this->input = Input::getInstance();
		$this->module = \F::config()->get('default_router.module');
		
		$this->_routing();
		
		self::$_instance = $this;
	}
	
	public static function getInstance(){
		if(!self::$_instance){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	private function _routing(){
		if (php_sapi_name() == 'cli' or defined('STDIN')){
			//命令行下执行
			$this->_parseCliArgs();
		}else{
			//http访问
			$this->_parseHttpArgs();
		}
	}
	
	private function _parseHttpArgs(){
		//若配置文件中未设置base_url，则系统猜测一个
		$base_url = \F::config()->get('base_url');
		
		if($base_url){
			//若未开启伪静态，需要加上index.php/
			if(defined('NO_REWRITE') && NO_REWRITE && substr($base_url, -10) != 'index.php/'){
				$base_url .= 'index.php/';
				\F::config()->set('base_url', $base_url);
			}
		}else{
			//未设置$base_url，系统猜测一个
			$base_url = Http::getBaseUrl();
			\F::config()->set('base_url', $base_url);
		}
		
		//若未设置静态文件路径，初始化
		if(!\F::config()->get('assets_url')){
			\F::config()->set('assets_url', $base_url . 'assets/');
		}
		//若未设置app静态文件路径，初始化
		if(!\F::config()->get('app_assets_url')){
			\F::config()->set('app_assets_url', $base_url . 'apps/' . APPLICATION . '/');
		}
		
		$base_url_params = parse_url($base_url);
		$base_url_path_length = strlen($base_url_params['path']);
		//过滤掉问号后面的部分
		if(strpos($_SERVER['REQUEST_URI'], '?') !== false){
			$request = substr($_SERVER['REQUEST_URI'], $base_url_path_length, strpos($_SERVER['REQUEST_URI'], '?') - $base_url_path_length);
		}else{
			$request = substr($_SERVER['REQUEST_URI'], $base_url_path_length);
		}
		
		$this->request_uri = $request;
		
		if($request == ''){
			//无路由信息，访问默认路由
			$default_router = \F::config()->get('default_router');
			$this->_setRouter($default_router['module'], $default_router['controller'], $default_router['action'], APPLICATION);
			return;
		}
		
		//匹配扩展名
		$ext = \F::config()->get('url_suffix');
		$exts = \F::config()->get('*', 'exts');
		foreach($exts as $key => $val){
			foreach($val as $v){
				if(preg_match('/^'.str_replace(array(
					'/', '*',
				), array(
					'\/', '.*',
				), $v).'$/i', $key ? substr($request, 0, 0 - strlen($key)) : $request)){
					$ext = $key;
					break 2;
				}
			}
		}
		if($ext != ''){
			if(substr($request, 0 - strlen($ext)) != $ext){
				//扩展名异常，无法进行路由
				$this->router = false;
				return;
			}else{
				$request = substr($request, 0, 0 - strlen($ext));
			}
		}
		
		//进行URL重写匹配
		$routes = \F::config()->get('*', 'routes');
		if(!empty($routes)){
			$request = preg_replace(array_keys($routes), array_values($routes), $request);
		}
		
		//以冒号作为router和参数之间的分割，一般用于url重写出来的，正常的参数还是放在问号后面比较好
		$parse_request = explode(':', $request, 2);
		$request = $parse_request[0];
		$params_uri = isset($parse_request[1]) ? $parse_request[1] : array();
		
		$request_arr = explode('/', $request);
		$request_arr_count = count($request_arr);
		switch($request_arr_count){
			case 1:
				//一级路由，取app，默认module，路由指定的controller和默认action，package为APPLICATION
				$this->_setRouter(\F::config()->get('default_router.module'), $request_arr[0], \F::config()->get('default_router.action'), APPLICATION);
				break;
			case 2:
				//二级路由，取app，默认module，路由指定的controller和action，package为APPLICATION
				$this->_setRouter(\F::config()->get('default_router.module'), $request_arr[0], $request_arr[1], APPLICATION);
				break;
			case 3:
				//三级路由，取app，路由指定的module，controller和action，package为APPLICATION
				$this->_setRouter($request_arr[0], $request_arr[1], $request_arr[2], APPLICATION);
				break;
			case 4:
				//四级路由，取faysoft/{$request_arr[0]}，路由指定的module，controller和action
				$this->_setRouter($request_arr[1], $request_arr[2], $request_arr[3], $request_arr[0]);
				break;
			default:
				
		}
		
		if($params_uri){
			parse_str($params_uri, $parse_params_uri);
			
			foreach($parse_params_uri as $k=>$p){
				$this->input->setGet($k, $p, false);
			}
		}
		
	}
	
	private function _setRouter($module = null, $controller = null, $action = null, $package = 'cms'){
		$module || $module = \F::config()->get('default_router.module');
		$controller || $controller = 'index';
		$action || $action = 'index';
		
		$this->router = "{$package}/{$module}/{$controller}/{$action}";
		
		$this->package = $package;
		$this->module = $module;
		$this->controller = StringHelper::hyphen2case($controller);
		$this->action = StringHelper::hyphen2case($action, false);
	}
	
	/**
	 * Cli方式运行
	 * 命令格式如下：
	 * php /var/www/html/fayfox.com/test/public/index.php tools/function/log text=console;
	 * php 文件路径 router 参数
	 */
	private function _parseCliArgs(){
		//第一个参数是路由信息
		$router = explode('/', $_SERVER['argv'][1]);
		$modules = array_merge(array('admin', 'tools', 'install', 'api'), \F::config()->get('modules'));
		if(in_array($router[0], $modules)){
			$this->_setRouter($router[0], isset($router[1]) ? $router[1] : null, isset($router[2]) ? $router[2] : null);
		}else{
			$this->_setRouter(null, $router[0], isset($router[1]) ? $router[1] : null);
		}
		
		$args = array_slice($_SERVER['argv'], 2);
		$params_uri = implode('&', $args);
		
		parse_str($params_uri, $parse_params_uri);
		
		foreach($parse_params_uri as $k=>$p){
			$this->input->setGet($k, $p, false);
		}
	}
}