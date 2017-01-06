<?php
use fay\helpers\HtmlHelper;
use fay\services\FileService;
?>
<div class="widget widget-friendlinks">
	<ul>
	<?php foreach($links as $l){?>
		<li><?php echo HtmlHelper::link(HtmlHelper::img($l['logo'], FileService::PIC_RESIZE, array(
			'dw'=>242,
			'dh'=>64,
		)), $l['url'], array(
			'encode'=>false,
			'title'=>$l['title'],
			'target'=>$l['target'],
		));?></li>
	<?php }?>
	</ul>
</div>