<?php
use fay\helpers\Html;

function renderMenu($menus){
	echo '<ul>';
	foreach($menus as $m){
		echo '<li'.(empty($m['children']) ? '' : ' class="has-sub"').'>';
		echo Html::link('<span class="title">'.$m['title'].'</span>', $m['link'], array(
			'encode'=>false,
			'title'=>false,
			'prepend'=>$m['css_class'] ? array(
				'tag'=>'i',
				'text'=>'',
				'class'=>$m['css_class'],
			) : false,
		));
		if(!empty($m['children'])){
			renderMenu($m['children']);
		}
		echo '</li>';
	}

	echo '</ul>';
}
?>
<div class="widget widget-menu" id="widget-<?php Html::encode($alias)?>">
	<?php renderMenu($menu)?>
</div>