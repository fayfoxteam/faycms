<?php
namespace fay\core;

use fay\helpers\StringHelper;
use fay\helpers\UrlHelper;

class View{
	/**
	 * 用于试图层的数据
	 * @var array
	 */
	private $_view_data = array();
	private $_null = null;
	
	private $_css = array();
	
	public function url($router = null, $params = array(), $url_rewrite = true){
		return UrlHelper::createUrl($router, $params, $url_rewrite);
	}
	
	/**
	 * 返回public/apps/{APPLICATION}下的文件路径
	 * 用于返回自定义application的静态文件
	 * @param string $uri
	 * @return string
	 */
	public function appStatic($uri){
		return UrlHelper::appStatic($uri);
	}
	
	/**
	 * 返回public/assets/下的文件路径（第三方jquery类库等）
	 * 主要是考虑到以后如果要做静态资源分离，只要改这个函数就好了
	 * @param string $uri
	 * @return string
	 */
	public function assets($uri){
		return UrlHelper::assets($uri);
	}
	
	/**
	 * 向视图传递一堆参数
	 * @param array $options
	 * @return View
	 */
	public function assign($options){
		$this->_view_data = array_merge($this->_view_data, $options);
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function getViewData(){
		return $this->_view_data;
	}
	
	/**
	 * 指定视图参数
	 * @param string $key
	 * @param string $value
	 */
	public function __set($key, $value){
		$this->_view_data[$key] = $value;
	}
	
	public function &__get($key){
		if(isset($this->_view_data[$key])){
			return $this->_view_data[$key];
		}else{
			return $this->_null;//直接返回null的话，会报错
		}
	}
	
	public function appendCss($href){
		array_push($this->_css, $href);
	}
	
	public function prependCss($href){
		array_unshift($this->_css, $href);
	}
	
	public function getCss(){
		$html = '';
		foreach($this->_css as $css){
			$html .= '<link type="text/css" rel="stylesheet" href="'.$css.'" />'."\r\n";
		}
		return $html;
	}
	
	/**
	 *渲染一个视图
	 * @param string $view 视图文件
	 * @param string $layout 模板文件目录
	 * @param bool $return
	 * @return null|string
	 * @throws Exception
	 */
	public function render($view = null, $layout = null, $return = false){
		//hook
		Hook::getInstance()->call('before_render');
		
		$uri = Uri::getInstance();
		$content = $this->renderPartial($view, array(), -1, true);
		
		$module = isset($uri->module) ? $uri->module : \F::config()->get('default_router.module');
		if($layout !== false){
			if($layout !== null){
				//加载模板文件
				$layout_relative_path = "modules/{$module}/views/layouts/{$layout}.php";
			}else if(!empty(\F::app()->layout_template)){
				$layout_relative_path = "modules/{$module}/views/layouts/".\F::app()->layout_template.'.php';
			}
			if(isset($layout_relative_path)){
				if(file_exists(APPLICATION_PATH.$layout_relative_path)){
					$__layout_path = APPLICATION_PATH.$layout_relative_path;
				}else if(file_exists(BACKEND_PATH.$layout_relative_path)){
					$__layout_path = BACKEND_PATH.$layout_relative_path;
				}else{
					throw new Exception("Layout file \"{$layout_relative_path}\" not found");
				}
			}
		}
		if(isset($__layout_path)){
			extract($this->getViewData(), EXTR_PREFIX_SAME, 'view');
			extract(\F::app()->layout->getLayoutData(), EXTR_PREFIX_SAME, 'view');
			ob_start();
			include $__layout_path;
			$content = ob_get_contents();
			ob_end_clean();
		}
		
		if($return){
			return $content;
		}else{
			Response::send($content);
			//自动输出debug信息
			if(\F::config()->get('debug')){
				$this->renderPartial('common/_debug');
			}
			
			return null;
		}
	}
	
	/**
	 * 不带layout渲染一个视图
	 * @param string $view
	 * @param array $view_data 传参
	 * @param int $__cache 局部缓存，大于0表示过期时间；等于0表示永不过期；小于0表示不缓存
	 * @param bool $__return 若为true，则不输出而是返回渲染结果
	 * @return NULL|string
	 * @throws ErrorException
	 */
	public function renderPartial($view = null, $view_data = array(), $__cache = -1, $__return = false){
		$uri = Uri::getInstance();
		$module = isset($uri->module) ? $uri->module : \F::config()->get('default_router.module');
		//加载视图文件
		if($view === null){
			$action = StringHelper::case2underscore($uri->action);
			$controller = StringHelper::case2underscore($uri->controller);
			$view_relative_path = "modules/{$module}/views/{$controller}/{$action}.php";
		}else{
			$view_arr = explode('/', $view, 3);
			
			switch(count($view_arr)){
				case 1:
					$controller = $uri->controller;
					$action = $view_arr[0];
				break;
				case 2:
					$controller = $view_arr[0];
					$action = $view_arr[1];
				break;
				case 3:
				default:
					$module = $view_arr[0];
					$controller = $view_arr[1];
					$action = $view_arr[2];
				break;
			}
			
			//大小写分割转下划线分割
			$controller = StringHelper::case2underscore($controller);
			$action = StringHelper::case2underscore($action);
			$view_relative_path = "modules/{$module}/views/{$controller}/{$action}.php";
		}
		
		if($__cache >= 0){
			//从缓存获取
			$cache_key = "partial/{$module}/{$controller}/{$action}";
			$content = \F::cache()->get($cache_key);
			if($content){
				if($__return){
					return $content;
				}else{
					echo $content;
					return null;
				}
			}
		}
		
		if(file_exists(APPLICATION_PATH.$view_relative_path)){
			//前台application
			$view_path = APPLICATION_PATH.$view_relative_path;
		}else if(file_exists(BACKEND_PATH.$view_relative_path)){
			//admin, tools等后台application
			$view_path = BACKEND_PATH.$view_relative_path;
		}else if(file_exists(BACKEND_PATH."modules/tools/views/{$controller}/{$action}.php")){
			//最后搜索tools下有没有默认文件，例如报错，分页条等
			$view_path = BACKEND_PATH."modules/tools/views/{$controller}/{$action}.php";
		}
		
		if(!isset($view_path)){
			throw new ErrorException('视图文件不存在', 'Relative Path: '.$view_relative_path);
		}else{
			extract(array_merge($this->getViewData(), $view_data));
			ob_start();
			include $view_path;
			$content = ob_get_contents();
			ob_end_clean();
		}
		
		if($__cache >= 0){
			//设置缓存
			\F::cache()->set($cache_key, $content, $__cache);
		}
		
		if($__return){
			return $content;
		}else{
			echo $content;
			return null;
		}
	}
}