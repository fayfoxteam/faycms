<?php
namespace fay\core\widget;

class View{
	/**
	 * 用于视图层的数据
	 * @var array
	 */
	private $_view_data = array();
	
	public $__name = '';
	
	private $_widget_class_name = '';
	
	public function __construct($name, $widget_class_name){
		$this->__name = $name;
		$this->_widget_class_name = $widget_class_name;
	}
	
	public function url($router = false, $params = array(), $url_rewrite = true){
		return \F::app()->view->url($router, $params, $url_rewrite);
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
	
	public function __get($key){
		if(isset($this->_view_data[$key])){
			return $this->_view_data[$key];
		}else{
			return null;
		}
	}
	
	public function appendCss($href){
		\F::app()->view->appendCss($href);
	}
	
	public function prependCss($href){
		\F::app()->view->prependCss($href);
	}
	
	public function render($view = null, $view_data = array(), $return = false){
		$view || $view = 'index';
		//获取Controller名
		$controller = strtolower(substr($this->_widget_class_name, strrpos($this->_widget_class_name, '\\')+1, -10));
		//获取view文件相对路径
		if(strpos($view, '/') === false){
			$view = $controller . DS . $view;
		}
		
		if(strpos($this->__name, '/') === false){
			$view_file = APPLICATION_PATH.'widgets'.DS.$this->__name.DS.'views'.DS.$view.'.php';
		}else{
			$name_explode = explode('/', $this->__name);
			$pre = array_shift($name_explode);
			if(substr($this->_widget_class_name, 0, strlen(APPLICATION)) == APPLICATION){
				$view_file = APPLICATION_PATH.'widgets'.DS.implode(DS, $name_explode).DS.'views'.DS.$view.'.php';
			}else{
				$view_file = SYSTEM_PATH.$pre.DS.'widgets'.DS.implode(DS, $name_explode).DS.'views'.DS.$view.'.php';
			}
		}
		
		extract(array_merge($this->getViewData(), $view_data));
		ob_start();
		include $view_file;
		$content = ob_get_contents();
		ob_end_clean();
		
		if($return){
			return $content;
		}else{
			echo $content;
		}
	}
	
	public function assets($uri){
		return \F::app()->view->assets($uri);
	}
	
	public function appStatic($uri){
		return \F::app()->view->appStatic($uri);
	}
}