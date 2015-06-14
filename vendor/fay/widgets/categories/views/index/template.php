<?php
namespace fay\widgets\categories\views\index;//一般不需要用命名空间，这里因为定义了一个函数，防止重名。

use fay\helpers\Html;

if(!function_exists('fay\widgets\categories\views\index\renderCats')){
	function renderCats($cats, $uri, $dep = 0){
		$html = '<ul';
		$html .= $dep ? ' class="children"' : '';
		$html .= '>';
		foreach($cats as $c){
			$html .= '<li class="cat-item">';
			$html .= Html::link($c['title'], array(str_replace(array(
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
<div class="widget widget-categories" id="widget-<?php echo Html::encode($alias)?>">
	<div class="widget-title">
		<h3><?php echo Html::encode($config['title'])?></h3>
	</div>
	<div class="widget-content">
		<?php echo renderCats($cats, $config['uri'])?>
	</div>
</div>