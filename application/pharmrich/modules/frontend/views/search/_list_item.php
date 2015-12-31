<?php
use fay\helpers\Html;
use pharmrich\helpers\PostHelper;
use fay\models\File;
use fay\helpers\String;

$type = PostHelper::getType($data['cat_id']);
?>
<article class="cf">
	<header class="cf">
		<h1><?php echo Html::link(str_replace($keywords, "<mark>{$keywords}</mark>", Html::encode($data['title'])), array("$type/{$data['id']}"), array(
			'encode'=>false,
		))?></h1>
		<span class="post-meta">
			<?php echo date('d M Y', $data['publish_time'])?>
			/
			<?php echo Html::link($data['cat_title'], array("$type/{$data['cat_alias']}"), array(
				'class'=>'fc-red',
			))?>
			/
			<span class="fc-red"><?php echo $data['views']?> Views</span>
		</span>
	</header>
	<?php if($data['thumbnail']){
		echo Html::link(Html::img($data['thumbnail'], File::PIC_RESIZE, array(
			'dw'=>300,
			'dh'=>230,
			'alt'=>Html::encode($data['title']),
		)), array("$type/{$data['id']}"), array(
			'encode'=>false,
			'class'=>'thumbnail',
			'title'=>Html::encode($data['title']),
		));
	}?>
	<p><?php echo nl2br(Html::encode(String::niceShort($data['abstract'], 250)))?></p>
	<?php echo Html::link('Read More', array("$type/{$data['id']}"), array(
		'class'=>'btn-red btn-sm mt20',
		'title'=>false,
	))?>
</article>