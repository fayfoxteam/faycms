<?php
use fay\helpers\Html;
?>
<aside class="col-md-4">
	<h4><?php echo Html::encode($title)?></h4>
	<div>
		<ul class="social-qr">
		<?php foreach($files as $f){?>
			<li><img src="<?php echo $f['src']?>" width="130" /></li>
		<?php }?>
		</ul>
	</div>
</aside>