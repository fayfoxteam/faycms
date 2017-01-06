<?php use fay\helpers\HtmlHelper;?>
<aside class="widget recent-post">
	<div class="widget-title"><?php echo HtmlHelper::encode($widget->config['title'])?></div>
	<ul>
	<?php foreach($posts as $p){?>
	<li><?php
		echo HtmlHelper::link($p['post']['title'], $p['post']['link']);
	?></li>
	<?php }?>
	</ul>
</aside>