<?php
namespace cms\helpers;

use fay\helpers\Html;
class MenuHelper{
	/**
	 * 渲染一个导航栏
	 */
	public static function render($menus, $current_directory, $dep = 0){
		//@todo 还要做权限判断
		if($dep){
			echo '<ul>';
		}else{
			echo '<ul class="main-menu" id="main-menu">';
		}
		foreach($menus as $m){
			$class = array();
			if(!empty($m['children'])){
				$class[] = 'has-sub';
			}
			if(($current_directory && $current_directory == $m['alias']) || \F::app()->uri->router == $m['link']){
				$class[] = 'opened';
				$class[] = 'expanded';
				$class[] = 'active';
			}
			echo '<li class="'.implode(' ', $class).'">';
			echo Html::link('<span class="title">'.$m['title'].'</span>', $m['link'] == 'javascript:;' ? 'javascript:;' : array($m['link']), array(
				'encode'=>false,
				'title'=>false,
				'prepend'=>$m['css_class'] ? array(
					'tag'=>'i',
					'text'=>'',
					'class'=>$m['css_class'],
				) : false,
			));
			if(!empty($m['children'])){
				self::render($m['children'], $current_directory, $dep + 1);
			}
			echo '</li>';
		}
		
		echo '</ul>';
	}
}