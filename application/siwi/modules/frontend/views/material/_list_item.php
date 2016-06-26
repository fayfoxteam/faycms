<?php
use fay\helpers\Html;
use fay\services\File;
use siwi\helpers\FriendlyLink;
?>
<article class="<?php if($index % 4 == 0)echo 'last'?>">
	<?php echo Html::link(Html::img($data['thumbnail'], File::PIC_RESIZE, array(
		'dw'=>283,
		'dh'=>217,
		'alt'=>Html::encode($data['title']),
		'title'=>Html::encode($data['title']),
		'spare'=>'default',
		'class'=>'thumbnail',
	)), array('material/'.$data['id']) ,array(
		'encode'=>false,
		'title'=>Html::encode($data['title']),
	));?>
	<div class="meta">
		<h3><?php echo Html::link($data['title'], array('material/'.$data['id']), array(
			'title'=>Html::encode($data['title']),
			'encode'=>false,
		))?></h3>
		<p class="cat">
			<?php echo Html::link($data['parent_cat_title'], FriendlyLink::get('material', $data['parent_cat_id']))?>
			-
			<?php echo Html::link($data['cat_title'], FriendlyLink::get('material', $data['parent_cat_id'], $data['cat_id']))?>
		</p>
	</div>
</article>