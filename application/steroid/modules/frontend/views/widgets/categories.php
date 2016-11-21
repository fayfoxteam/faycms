<?php
use fay\helpers\Html;
?>
<div class="widget widget-categories">
	<h5 class="widget-title"><?php echo Html::encode($config['title'])?></h5>
	<ul>
	<?php foreach($cats as $c){?>
		<li>
			<?php echo Html::link($c['title'], $c['link'])?>
			<span>(<?php echo $c['count']?>)</span>
		</li>
	<?php }?>
	</ul>
</div>