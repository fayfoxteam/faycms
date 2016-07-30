<?php
use fay\helpers\Html;
?>
<aside class="col-md-4">
	<h4><?php echo Html::encode($title)?></h4>
	<div>
		<ul class="social-qr">
		<?php foreach($files as $f){?>
			<li><?php echo Html::img($f['src'], 0, array(
				'width'=>130,
			))?><span><?php echo Html::encode($f['title'])?></span></li>
		<?php }?>
		</ul>
	</div>
</aside>