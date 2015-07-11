<?php
use fay\helpers\Html;
?>
<aside class="box">
	<div class="box-title">
		<h3 class="sub-title"><?php echo Html::encode($config['title'])?></h3>
	</div>
	<div class="box-content">
		<ul class="box-cats">
		<?php foreach($cats as $c){?>
			<li><?php echo Html::link($c['title'], array(
				'product/'.$c['alias']
			))?></li>
		<?php }?>
		</ul>
	</div>
</aside>