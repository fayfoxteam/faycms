<?php
namespace fay\widgets\categories\views\index;

use fay\helpers\Html;

/**
 * @var $widget \fay\widgets\categories\controllers\IndexController
 * @var $cats array
 */

if(!function_exists('fay\widgets\categories\views\index\renderCats')){
	function renderCats($cats, $dep = 0){
		$html = '<ul';
		$html .= $dep ? ' class="children"' : '';
		$html .= '>';
		foreach($cats as $c){
			$html .= '<li class="cat-item">';
			$html .= Html::link($c['title'], $c['link']);
			if(!empty($c['children'])){
				$html .= renderCats($c['children'], ++$dep);
			}
			$html .= '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
}
?>
<div class="widget widget-categories" id="widget-<?php echo Html::encode($widget->alias)?>">
	<div class="widget-title">
		<h3><?php echo Html::encode($widget->config['title'])?></h3>
	</div>
	<div class="widget-content">
		<?php echo renderCats($cats)?>
	</div>
</div>