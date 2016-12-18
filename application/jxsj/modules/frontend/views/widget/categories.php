<?php
use fay\helpers\Html;

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
?>
<div class="box category-post">
	<div class="box-title">
		<h3><?php echo Html::encode($widget->config['title'])?></h3>
	</div>
	<div class="box-content">
		<div class="st"><div class="sl"><div class="sr"><div class="sb">
			<div class="p16 clearfix">
				<?php echo renderCats($cats)?>
			</div>
		</div></div></div></div>
	</div>
</div>