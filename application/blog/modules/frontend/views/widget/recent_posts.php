<?php use fay\helpers\Html;?>
<aside class="widget recent-post">
	<div class="widget-title"><?php echo Html::encode($widget->config['title'])?></div>
	<ul>
	<?php foreach($posts as $p){?>
	<li><?php
		echo Html::link($p['post']['title'], $p['post']['link']);
	?></li>
	<?php }?>
	</ul>
</aside>