<?php
use fay\helpers\Html;
?>
<div class="page-title">
	<h1><?php echo Html::encode($post['post']['title'])?></h1>
</div>
<div class="meta">
	<?php echo date('d M Y', $post['post']['publish_time'])?>
	/
	<span class="fc-red"><?php echo $post['post']['views']?> Views</span>
	/
	<?php echo Html::link($post['post']['cat_title'], array('product/' . $post['post']['cat_alias']))?>
</div>
<div class="page-content cf"><?php echo $post['post']['content']?></div>