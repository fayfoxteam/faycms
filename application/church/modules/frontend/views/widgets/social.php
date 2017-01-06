<?php
use fay\helpers\HtmlHelper;
?>
<aside class="col-md-4">
	<h4><?php echo HtmlHelper::encode($title)?></h4>
	<div>
		<ul class="social-qr">
		<?php foreach($files as $f){?>
			<li><?php echo HtmlHelper::img($f['src'], 0, array(
				'width'=>130,
			))?><span><?php echo HtmlHelper::encode($f['title'])?></span></li>
		<?php }?>
		</ul>
	</div>
</aside>