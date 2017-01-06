<?php
namespace uuice\helpers;

use fay\helpers\HtmlHelper;
class MenuHelper{
	/**
	 * 渲染一个导航栏
	 * @param array $cats
	 */
	public static function render($cats, $current_cat, $dep = 0){
		if($dep){
			echo '<ul>';
		}else{
			echo '<ul class="main-menu" id="main-menu">';
		}
		foreach($cats as $c){
			$class = array();
			if(!empty($c['children'])){
				$class[] = 'has-sub';
			}
			if(!$current_cat && $dep < 2){
				$class[] = 'opened';
				$class[] = 'expanded';
			}else if($current_cat && $current_cat['left_value'] >= $c['left_value'] && $current_cat['right_value'] <= $c['right_value']){
				$class[] = 'opened';
				$class[] = 'expanded';
				$class[] = 'active';
			}
			echo '<li class="'.implode(' ', $class).'">';
			echo HtmlHelper::link('<span class="title">'.$c['title'].($c['description'] ? "（{$c['description']}）" : '').'</span>', array($c['alias'] ? $c['alias'] : $c['id']), array(
				'encode'=>false,
				'title'=>HtmlHelper::encode($c['title']),
			));
			if(!empty($c['children'])){
				self::render($c['children'], $current_cat, $dep + 1);
			}
			echo '</li>';
		}
		
		echo '</ul>';
	}
}