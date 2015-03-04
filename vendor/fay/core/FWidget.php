<?php
namespace fay\core;

use fay\core\FBase;
use fay\models\tables\Widgets;

class FWidget extends FBase{
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
			$class_name = APPLICATION.'\widgets\\'.$name.'\controllers\\'.$controller;
			return new $class_name(array(
				'name'=>$name,
			));
		}else{
			$name_explode = explode('/', $name);
			$pre = array_shift($name_explode);
			$class_name = $pre.'\widgets\\'.implode('/', $name_explode).'\controllers\\'.$controller;
			return new $class_name(array(
				'name'=>$name,
			));
		}
	}
	
	/**
	 * 根据数据库中的别名，实例化对应的widget，进行渲染
	 */
	public function load($alias, $ajax = false, $cache = false){
		$widget_config = Widgets::model()->fetchRow(array(
			'alias = ?'=>$alias,
		));
		if($widget_config['enabled']){
			$this->render($widget_config['widget_name'], json_decode($widget_config['options'], true), $ajax, $cache, $alias);
		}
	}
	
	/**
	 * 根据widget name调用index方法，直接渲染一个widget
	 */
	public function render($name, $options = array(), $ajax = false, $cache = false, $alias = ''){
		if($cache && $content = \F::app()->cache->get(md5('widget_'.$name.serialize($options)))){
			echo $content;
		}else{
			$widget_obj = $this->get($name);
			if($widget_obj == null){
				echo 'widget不存在或已被删除';
			}else{
				$widget_obj->alias = $alias;
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
						echo "<div id='{$id}'><img src='".\F::app()->view->url()."images/throbber.gif' /></div>";
					}
					echo '<script>
						$.ajax({
							type: "GET",
							url: "'.\F::app()->view->url(\F::app()->uri->module.'/widget/render', array('name'=>$name) + $options, false).'",
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
	
	public function getData($alias){
		$widget = Widgets::model()->fetchRow(array(
			'alias = ?'=>$alias,
		), 'options');
		return json_decode($widget['options'], true);
	}
}