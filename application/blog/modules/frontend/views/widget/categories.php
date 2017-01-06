<?php
namespace blog\modules\frontend\views\widget;

use fay\helpers\HtmlHelper;

/**
 * @var $widget
 * @var array $cats
 */

if(!function_exists('blog\modules\frontend\views\widget\renderCats')){
	function renderCats($cats, $uri, $dep = 0){
		$html = '<ul';
		$html .= $dep ? ' class="children"' : '';
		$html .= '>';
		foreach($cats as $c){
			$html .= '<li>';
			$html .= HtmlHelper::link($c['title'], array(str_replace(array(
				'{$id}', '{$alias}',
			), array(
				$c['id'], $c['alias'],
			), $uri)));
			if(!empty($c['children'])){
				$html .= renderCats($c['children'], $uri, ++$dep);
			}
			$html .= '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
}
?>
<aside class="widget category">
	<div class="widget-title"><?php echo HtmlHelper::encode($widget->config['title'])?></div>
	<?php echo renderCats($cats, $widget->config['uri'])?>
</aside>