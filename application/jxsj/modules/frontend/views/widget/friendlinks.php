<?php
use fay\helpers\Html;
?>
<div class="box" id="box-friendlinks">
	<div class="box-content">
		<div class="st"><div class="sl"><div class="sr"><div class="sb">
			<div class="p16 clearfix">
				<h2><?php echo Html::encode($data['title'])?></h2>
				<?php foreach($links as $l){?>
					<p><?php echo Html::link($l['title'], $l['url'], array(
						'title'=>$l['description'] ? $l['description'] : $l['title'],
					));?></p>
				<?php }?>
			</div>
		</div></div></div></div>
	</div>
</div>