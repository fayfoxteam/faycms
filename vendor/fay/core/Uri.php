<?php
namespace fay\core;

use fay\helpers\String;

/**
 * 对url进行路由解析
 * @author karma
 *
 */
class Uri{
	public $router;
	public $module;
	
	/**
	 * 与$_SERVER['REQUEST_URI']想必，如果有二级或多级目录，会去掉目录
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
 	
	private static $_instance;
	
	public function __construct(){
		$this->input = Input::getInstance();
		$this->module = \F::config()->get('default_router.module');
		
		$this->_setRouting();
		
		self::$_instance = $this;
	}
	
	public static function getInstance(){
		return self::$_instance;
	}
	
	private function _setRouting(){
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
			$folder = dirname(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
			//所有斜杠都以正斜杠为准
			$folder = str_replace('\\', '/', $folder);
			if(substr($folder, -7) == '/public'){
				$folder = substr($folder, 0, -7);
			}
			if($folder && substr($folder, 0, 1) != '/'){
				//由于配置关系，有的DOCUMENT_ROOT最后有斜杠，有的没有
				$folder = '/'.$folder;
			}
			if($folder == '/'){
				//仅剩一根斜杠的时候（把根目录设到public目录下的情况），设为空
				$folder = '';
			}
			$base_url = 'http://'.(isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']).$folder.'/';
			if(defined('NO_REWRITE')){
				$base_url .= 'index.php/';
			}
			\F::config()->set('base_url', $base_url);
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
			$this->_setRouter($default_router['module'], $default_router['controller'], $default_router['action']);
			return;
		}
		
		//匹配扩展名
		$ext = \F::config()->get('url_suffix');
		$exts = \F::config()->get('*', 'exts', 'merge_recursive');
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
		
		$request_arr = explode('/', $request);
		$modules = array_merge(array('admin', 'tools', 'install', 'api'), \F::config()->get('modules'));
		if(in_array($request_arr[0], $modules)){
			//前3级是路由
			$this->_setRouter($request_arr[0], isset($request_arr[1]) ? $request_arr[1] : null, isset($request_arr[2]) ? $request_arr[2] : null);
			$params = array_slice($request_arr, 3);
		}else{
			//默认模块，前2级是路由
			$this->_setRouter(null, $request_arr[0], isset($request_arr[1]) ? $request_arr[1] : null);
			$params = array_slice($request_arr, 2);
		}
		
		$params_count = count($params);
		$params_uri = array();
		for($i = 0; $i < $params_count; $i++){
			if(isset($params[$i+1])){
				$params_uri[] = $params[$i] . '=' . $params[++$i];
			}
		}
		$params_uri = implode('&', $params_uri);
		
		parse_str($params_uri, $parse_params_uri);
		
		foreach($parse_params_uri as $k=>$p){
			$this->input->setGet($k, $p, false);
		}
	}
	
	private function _setRouter($module = null, $controller = null, $action = null){
		$module || $module = \F::config()->get('default_router.module');
		$controller || $controller = 'index';
		$action || $action = 'index';
		
		$this->router = "{$module}/{$controller}/{$action}";
			
		$this->module = $module;
		$this->controller = String::hyphen2case($controller);
		$this->action = String::hyphen2case($action, false);
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
		$modules = array_merge(array('admin', 'tools'), \F::config()->get('modules'));
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