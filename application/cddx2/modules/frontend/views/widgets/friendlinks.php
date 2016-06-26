<?php
use fay\helpers\Html;
use fay\services\File;
?>
<div class="widget widget-friendlinks">
	<ul>
	<?php foreach($links as $l){?>
		<li><?php echo Html::link(Html::img($l['logo'], File::PIC_RESIZE, array(
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