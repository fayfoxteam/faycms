<?php
use fay\helpers\Html;
?>
<div class="widget widget-category-posts" id="widget-<?php echo Html::encode($alias)?>">
	<div class="widget-title">
		<h3><?php echo Html::encode($config['title'])?></h3>
	</div>
	<div class="widget-content">
		<ul>
		<?php foreach($posts as $p){?>
			<li><?php
				echo Html::link($p['title'], array(str_replace('{$id}', $p['id'], $config['uri'])));
				echo $p['format_publish_time'];
			?></li>
		<?php }?>
		</ul>
	</div>
</div>