<?php
use fay\helpers\Html;
?>
<div class="widget widget-category-pages" id="widget-<?php echo Html::encode($alias)?>">
	<div class="widget-title">
		<h3><?php echo Html::encode($config['title'])?></h3>
	</div>
	<div class="widget-content">
		<ul>
		<?php foreach($pages as $p){?>
			<li><?php
				echo Html::link($p['title'], $p['link']);
			?></li>
		<?php }?>
		</ul>
	</div>
</div>