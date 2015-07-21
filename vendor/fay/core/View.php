<?php
namespace fay\core;

class View{
	/**
	 * 用于试图层的数据
	 * @var array
	 */
	private $_view_data = array();
	private $_null = null;
	
	private $_css = array();
	
	public function url($router = null, $params = array(), $url_rewrite = true){
		$base_url = \F::config()->get('base_url');
		if(!$router){
			return $base_url;
		}else{
			$default_module = \F::config()->get('default_router.module');
			if(strpos($router, $default_module . '/') === 0){
				$router = substr($router, strlen($default_module) + 1);
			}
			$ext = \F::config()->get('url_suffix');
			$exts = \F::config()->get('*', 'exts', 'merge_recursive');
			foreach($exts as $key => $val){
				foreach($val as $v){
					if(preg_match('/^'.str_replace(array(
						'/', '*',
					), array(
						'\/', '.*',
					), $v).'$/i', $router)){
						$ext = $key;
						break 2;
					}
				}
			}
			
			if($url_rewrite){
				//完整的url重写
				if($params){
					return $base_url . $router . '/' . str_replace(array('=', '&'), '/', http_build_query($params)) . $ext;
				}else{
					return $base_url . $router . $ext;
				}
			}else{
				//对params部分不做url重写
				if($params){
					return $base_url . $router . $ext . '?' . http_build_query($params);
				}else{
					return $base_url . $router . $ext;
				}
			}
		}
	}
	
	/**
	 * 返回public/apps/{APPLICATION}下的文件路径
	 * 用于返回自定义application的静态文件
	 * @param string $uri
	 * @return string
	 */
	public function appStatic($uri){
		return \F::config()->get('base_url') . 'apps/' . APPLICATION . '/' . $uri;
	}
	
	/**
	 * 返回public/assets/下的文件路径（第三方jquery类库等）
	 * 主要是考虑到以后如果要做静态资源分离，只要改这个函数就好了
	 * @param string $uri
	 * @return string
	 */
	public function assets($uri){
		return \F::config()->get('base_url') . 'assets/' . $uri;
	}
	
	/**
	 * 向视图传递一堆参数
	 * @param array $options
	 */
	public function assign($options){
		$this->_view_data = array_merge($this->_view_data, $options);
		return $this;
	}
	
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
	 * @param array $layout_data 传递给模板的参数
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
		
		//根据router设置缓存
		$cache_routers = \F::config()->get('*', 'pagecache');
		$cache_routers_keys = array_keys($cache_routers);
		if(in_array($uri->router, $cache_routers_keys)){
			$filename = md5(json_encode(\F::input()->get(isset($cache_routers[$uri->router]['params']) ? $cache_routers[$uri->router]['params'] : array())));
			$cache_key = 'pages/' . $uri->router . '/' . $filename;
			if(\F::input()->post()){
				//有post数据的时候，是否更新页面
				if(isset($cache_routers[$uri->router]['on_post'])){
					if($cache_routers[$uri->router]['on_post'] == 'rebuild'){//刷新缓存
						\F::cache()->set($cache_key, $content, $cache_routers[$uri->router]['ttl']);
					}else if($cache_routers[$uri->router]['on_post'] == 'remove'){//删除缓存
						\F::cache()->delete($cache_key);
					}
				}
			}else{
				//没post数据的时候，直接重新生成页面缓存
				\F::cache()->set($cache_key, $content, $cache_routers[$uri->router]['ttl']);
			}
		}
		
		if($return){
			return $content;
		}else{
			echo $content;
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
	 * @param bool $return 若为true，则不输出而是返回渲染结果
	 * @param int $cache 局部缓存，大于0表示过期时间；等于0表示永不过期；小于0表示不缓存
	 * @return string|NULL
	 */
	public function renderPartial($view = null, $view_data = array(), $__cache = -1, $__return = false){
		$uri = Uri::getInstance();
		$module = isset($uri->module) ? $uri->module : \F::config()->get('default_router.module');
		//加载视图文件
		if($view === null){
			$view = strtolower($uri->action);
			$controller = strtolower($uri->controller);
			$view_relative_path = "modules/{$module}/views/{$controller}/{$view}.php";
		}else{
			$view_arr = explode('/', $view);
			
			switch(count($view_arr)){
				case 1:
					$controller = strtolower($uri->controller);
					$action = $view_arr[0];
				break;
				case 2:
					$controller = $view_arr[0];
					$action = $view_arr[1];
				break;
				case 3:
					$module = $view_arr[0];
					$controller = $view_arr[1];
					$action = $view_arr[2];
				break;
			}
			
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
			throw new Exception('视图文件不存在', 'Relative Path: '.$view_relative_path);
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