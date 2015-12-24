<?php
use fay\helpers\Html;
use fay\models\File;
?>
<div class="widget posts">
	<h3><?php echo $config['title']?></h3>
	<ul><?php foreach($posts as $p){?>
		<li>
			<?php if($p['thumbnail']){
				echo Html::link(Html::img($p['thumbnail'], File::PIC_RESIZE, array(
					'dw'=>150,
					'dh'=>115,
					'alt'=>Html::encode($p['title']),
				)), $p['link'], array(
					'encode'=>false,
					'title'=>Html::encode($p['title']),
				));
			}?>
			<h5><?php echo Html::link($p['title'], $p['link'])?></h5>
			<span><?php echo $p['format_publish_time']?> / <span class="fc-red"><?php echo $p['views']?> Views</span></span>
		</li>
	<?php }?></ul>
</div>