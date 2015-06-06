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
			$html .= renderCats($c['children'], $uri, ++$dep);
		}
		$html .= '</li>';
	}
	$html .= '</ul>';
	return $html;
}
?>
<div class="box category-post">
	<div class="box-title">
		<h3><?php echo Html::encode($data['title'])?></h3>
	</div>
	<div class="box-content">
		<div class="st"><div class="sl"><div class="sr"><div class="sb">
			<div class="p16 clearfix">
				<?php echo renderCats($cats, $data['uri'])?>
			</div>
		</div></div></div></div>
	</div>
</div>