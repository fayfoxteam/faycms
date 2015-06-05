<?php
use fay\helpers\Html;

function renderCats($cats, $uri, $dep = 0){
	$html = '<ul';
	$html .= $dep ? ' class="children"' : '';
	$html .= '>';
	foreach($cats as $c){
		$html .= '<li>';
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
?>
<aside class="widget category">
	<div class="widget-title"><?php echo Html::encode($data['title'])?></div>
	<?php echo renderCats($cats, $data['uri'])?>
</aside>