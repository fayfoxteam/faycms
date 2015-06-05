<?php
use fay\helpers\Html;
?>
<div class="link mt40">
	<div class="link_info">
	<?php foreach($links as $l){?>
		<?php echo Html::link($l['title'], $l['url'], array(
			'target'=>$l['target'],
		));?>
	<?php }?>
	</div>
</div>