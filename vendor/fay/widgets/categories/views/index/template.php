<?php
use fay\helpers\Html;

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
			$html .= $this->renderCats($c['children'], $uri, ++$dep);
		}
		$html .= '</li>';
	}
	$html .= '</ul>';
	return $html;
}
?>
<div class="widget widget-categories" id="widget-<?php echo Html::encode($alias)?>">
	<div class="widget-title">
		<h3><?php echo Html::encode($data['title'])?></h3>
	</div>
	<div class="widget-content">
		<?php echo renderCats($cats, $data['uri'])?>
	</div>
</div>