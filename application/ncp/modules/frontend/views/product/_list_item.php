<?php
use fay\helpers\Html;
use fay\models\File;
use ncp\helpers\FriendlyLink;
?>
<li>
	<div class="p-img"><?php echo Html::link(Html::img($data['thumbnail'], File::PIC_RESIZE, array(
		'dw'=>280,
		'dh'=>210,
		'alt'=>Html::encode($data['title']),
	)), FriendlyLink::getProductLink(array(
		'id'=>$data['id']
	)), array(
		'encode'=>false,
		'title'=>Html::encode($data['title']),
	))?></div>
	<div class="p-name">
		<?php echo Html::link($data['title'], FriendlyLink::getProductLink(array(
			'id'=>$data['id']
		)), array(
			'target'=>'_blank',
		))?>
	</div>
	<div class="p-st">
		<span class="fl"><b>产地：</b><?php echo $data['area']?></span>
		<span class="fr"><b>分类：</b><?php echo $data['cat_title']?></span>
	</div>
</li>