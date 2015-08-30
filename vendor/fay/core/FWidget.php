<?php
namespace fay\core;

use fay\models\tables\Widgets;
use fay\helpers\String;

class FWidget{
	/**
	 * 实例化一个widget，并返回实例
	 * @return Widget
	 */
	public function get($name, $admin = false){
		if($admin){
			$controller = 'AdminController';
		}else{
			$controller = 'IndexController';
		}
		
		if(strpos($name, '/') === false){
			//用户自定义的widget name不带斜杠
			$class_name = APPLICATION.'\widgets\\'.$name.'\controllers\\'.$controller;
			$path = APPLICATION_PATH . 'widgets/' . $name . '/';
			return new $class_name($name, $path);
		}else{
			//系统自带的widget name为cms/*或者fay/*
			$name_explode = explode('/', $name);
			$pre = array_shift($name_explode);//pre取值为cms或fay
			$class_name = $pre.'\widgets\\'.implode('/', $name_explode).'\controllers\\'.$controller;
			$path = SYSTEM_PATH . $pre . '/widgets/' . implode('/', $name_explode) . '/';
			return new $class_name($name, $path);
		}
	}
	
	/**
	 * 根据数据库中的别名，实例化对应的widget，进行渲染
	 */
	public function load($widget, $ajax = false, $cache = false, $_index = null){
		if(!is_array($widget)){
			if(String::isInt($widget)){
				$widget = Widgets::model()->find($widget);
			}else{
				$widget = Widgets::model()->fetchRow(array(
					'alias = ?'=>$widget,
				));
			}
		}
		
		if($widget && $widget['enabled']){
			$this->render($widget['widget_name'], json_decode($widget['options'], true), $ajax, $cache, $widget['alias'], $_index);
		}
	}
	
	/**
	 * 根据widget name调用index方法，直接渲染一个widget
	 */
	public function render($name, $options = array(), $ajax = false, $cache = false, $alias = '', $_index = null){
		if($cache && $content = \F::app()->cache->get(md5('widget_'.$name.serialize($options)))){
			echo $content;
		}else{
			$widget_obj = $this->get($name);
			if($widget_obj == null){
				echo 'widget不存在或已被删除';
			}else{
				$widget_obj->alias = $alias;//别名
				$widget_obj->_index = $_index;//在小工具域中的位置，若不是小工具域中调用，则为null
				if($ajax){
					//先占个位，然后用ajax引入widget
					$id = uniqid();
					if(method_exists($widget_obj, 'placeholder')){
						//若定义了占位式样，则显示定义的式样
						echo "<div id='{$id}'>";
						$widget_obj->placeholder();
						echo '</div>';
					}else{
						//若未定义，显示一个loading的图片
						echo "<div id='{$id}'><img src='".\F::app()->view->assets('images/throbber.gif')."' /></div>";
					}
					echo '<script>
						$.ajax({
							type: "GET",
							url: "'.\F::app()->view->url(\F::app()->uri->module.'/widget/render', array('name'=>$name, '_alias'=>$widget_obj->alias, '_index'=>$widget_obj->_index) + $options, false).'",
							cache: false,
							success: function(resp){
								$("#'.$id.'").replaceWith(resp);
							}
						});
					</script>';
				}else{
					ob_start();
					$widget_obj->index($options);
					$content = ob_get_contents();
					ob_end_clean();
					if($cache){
						\F::app()->cache->set(md5('widget_'.$name.serialize($options)), $content);
					}
					echo $content;
				}
			}
		}
	}
	
	/**
	 * 返回一个小工具的参数
	 * //@todo 改成getConfig比较合适
	 * @param string $alias 小工具实例别名
	 * @return array
	 */
	public function getData($alias){
		$widget = Widgets::model()->fetchRow(array(
			'alias = ?'=>$alias,
		), 'options');
		return json_decode($widget['options'], true);
	}
	
	/**
	 * 渲染一个小工具域
	 * @param string $alias 小工具域别名
	 */
	public function area($alias){
		if(empty($alias)){
			throw new Exception('引用小工具域时，别名不能为空');
		}
		$widgets = Widgets::model()->fetchAll("widgetarea = '{$alias}'", '*', 'sort,id');
		foreach($widgets as $k => $w){
			$this->load($w, $w['ajax'], $w['cache'], $k+1);
		}
	}
}