<?php
use fay\helpers\Html;
use fay\services\FileService;
use fay\helpers\StringHelper;
?>
<article class="cf">
	<?php echo Html::link(Html::img($data['thumbnail'], FileService::PIC_RESIZE, array(
		'dw'=>250,
		'dh'=>195,
		'alt'=>Html::encode($data['title']),
	)), array("{$cat['alias']}-{$data['id']}"), array(
		'encode'=>false,
		'title'=>Html::encode($data['title']),
		'class'=>'thumbnail',
	));?>
	<div class="post-info">
		<h2><?php echo Html::link($data['title'], array("{$cat['alias']}-{$data['id']}"))?></h2>
		<time class="publish-time"><?php echo date('Y年m月d日', $data['publish_time'])?></time>
		<div class="abstract"><?php echo StringHelper::nl2p(Html::encode($data['abstract']))?></div>
		<?php echo Html::link('Read More', array("{$cat['alias']}-{$data['id']}"), array(
			'class'=>'read-more',
		))?>
	</div>
</article>