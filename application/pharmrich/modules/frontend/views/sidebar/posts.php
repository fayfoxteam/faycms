<?php
use fay\helpers\Html;
?>
<div class="widget posts">
	<h3><?php echo $config['title']?></h3>
	<ul><?php foreach($posts as $p){?>
		<li>
			<h5><?php echo Html::link($p['title'], $p['link'])?></h5>
			<span><?php echo $p['format_publish_time']?> / <span class="fc-red"><?php echo $p['views']?> Views</span></span>
		</li>
	<?php }?></ul>
</div>