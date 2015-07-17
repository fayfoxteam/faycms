<?php
use fay\helpers\Html;
use fay\models\File;
?>
<ul class="clearfix">
<?php foreach($posts as $post){?>
	<li>
		<div class="inner">
			<figure><?php echo Html::img($post['thumbnail'], File::PIC_RESIZE, array(
				'dw'=>362,
				'dh'=>240,
			))?></figure>
			<div class="mask"></div>
			<h2><?php echo Html::link($post['title'], array(
				'product/'.$post['id']
			))?></h2>
			<div class="abstract"><?php echo Html::encode($post['abstract'])?></div>
		</div>
	</li>
<?php }?>
</ul>