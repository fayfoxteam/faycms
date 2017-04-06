<?php
namespace fay\core;

use fay\helpers\RuntimeHelper;
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
	
	public function url($router = null, $params = array()){
		return UrlHelper::createUrl($router, $params);
	}
	
	/**
	 * 返回public/apps/{APPLICATION}下的文件路径
	 * 用于返回自定义application的静态文件
	 * @param string $uri
	 * @return string
	 */
	public function appAssets($uri){
		return UrlHelper::appAssets($uri);
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
	 * @param mixed $value
	 * @return void
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
		RuntimeHelper::append(__FILE__, __LINE__, '准备渲染视图');
		//触发事件
		\F::event()->trigger('before_render');
		
		$uri = Uri::getInstance();
		$content = $this->renderPartial($view, $this->getViewData(), -1, true);
		RuntimeHelper::append(__FILE__, __LINE__, '视图渲染完成');
		
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
					$layout_path = APPLICATION_PATH.$layout_relative_path;
				}else if(file_exists(CMS_PATH.$layout_relative_path)){
					$layout_path = CMS_PATH.$layout_relative_path;
				}else{
					throw new Exception("Layout file \"{$layout_relative_path}\" not found");
				}
			}
		}
		if(isset($layout_path)){
			RuntimeHelper::append(__FILE__, __LINE__, '准备渲染模版');
			$content = $this->obOutput($layout_path, array_merge(
				$this->getViewData(),
				\F::app()->layout->getLayoutData(),
				array('content'=>$content)
			));
			RuntimeHelper::append(__FILE__, __LINE__, '模版渲染完成');
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
	 * @param array $view_data 传参（此函数不调用全局的传参，只认传入的参数）
	 * @param int $cache 局部缓存，大于0表示过期时间；等于0表示永不过期；小于0表示不缓存
	 * @param bool $return 若为true，则不输出而是返回渲染结果
	 * @return NULL|string
	 * @throws ErrorException
	 */
	public function renderPartial($view = null, $view_data = array(), $cache = -1, $return = false){
		RuntimeHelper::append(__FILE__, __LINE__, '开始渲染视图: ' . $view);
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
		
		if($cache >= 0){
			//从缓存获取
			$cache_key = "partial/{$module}/{$controller}/{$action}";
			$content = \F::cache()->get($cache_key);
			if($content){
				if($return){
					return $content;
				}else{
					echo $content;
					return null;
				}
			}
		}
		
		if($uri->package == 'cms' && file_exists(APPLICATION_PATH.$view_relative_path)){
			//前台app
			$view_path = APPLICATION_PATH.$view_relative_path;
		}else if(file_exists(FAYSOFT_PATH."{$uri->package}/{$view_relative_path}")){
			//faysoft/*下的类库
			$view_path = FAYSOFT_PATH."{$uri->package}/{$view_relative_path}";
		}else if(file_exists(CMS_PATH."modules/tools/views/{$controller}/{$action}.php")){
			//最后搜索cms/tools下有没有默认文件，例如报错，分页条等
			$view_path = CMS_PATH."modules/tools/views/{$controller}/{$action}.php";
		}
		
		if(!isset($view_path)){
			throw new ErrorException('视图文件不存在', 'Relative Path: '.$view_relative_path);
		}else{
			$content = $this->obOutput($view_path, $view_data);
		}
		
		if(isset($cache_key)){
			//设置缓存
			\F::cache()->set($cache_key, $content, $cache);
		}
		
		if($return){
			return $content;
		}else{
			echo $content;
			return null;
		}
	}
	
	/**
	 * eval执行一段代码，放在这个函数里是为了让eval的view层代码可以使用$this
	 * @param $code
	 * @param $data
	 */
	public function evalCode($__code__, $data){
		extract($data, EXTR_SKIP);
		eval('?>'.$__code__.'<?php ');
	}
	
	/**
	 * 独立一个渲染函数，防止变量污染
	 * @param string $__view_path__ 视图文件路径
	 * @param array $view_data 传递变量
	 * @return string
	 */
	private function obOutput($__view_path__, $view_data = array()){
		extract($view_data, EXTR_SKIP);
		ob_start();
		include $__view_path__;
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
	}
}