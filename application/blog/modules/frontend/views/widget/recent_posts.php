<?php use fay\helpers\Html;?>
<aside class="widget recent-post">
	<div class="widget-title"><?php echo Html::encode($data['title'])?></div>
	<ul>
	<?php foreach($posts as $p){?>
	<li><?php
		echo Html::link($p['title'], array(str_replace('{$id}', $p['id'], $data['uri'])));
	?></li>
	<?php }?>
	</ul>
</aside>