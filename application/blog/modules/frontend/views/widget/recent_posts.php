<?php use fay\helpers\Html;?>
<aside class="widget recent-post">
	<div class="widget-title"><?php echo Html::encode($config['title'])?></div>
	<ul>
	<?php foreach($posts as $p){?>
	<li><?php
		echo Html::link($p['post']['title'], array(str_replace('{$id}', $p['post']['id'], $config['uri'])));
	?></li>
	<?php }?>
	</ul>
</aside>