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
	)), FriendlyLink::getFoodLink(array(
		'id'=>$data['id']
	)), array(
		'encode'=>false,
		'title'=>Html::encode($data['title']),
	))?></div>
	<div class="p-name">
		<?php echo Html::link($data['title'], FriendlyLink::getFoodLink(array(
			'id'=>$data['id']
		)), array(
			'target'=>'_blank',
		))?>
	</div>
	<div class="p-maoshu"><?php echo Html::encode($data['abstract'])?></div>
	<div class="p-st">
		<span class="fl"><?php echo $data['views']?></span>
		<span class="fr"><?php echo Html::link('我要吃', FriendlyLink::getFoodLink(array(
			'id'=>$data['id'],
		)), array(
			'class'=>'gowhere',
		))?></span>
	</div>
</li>