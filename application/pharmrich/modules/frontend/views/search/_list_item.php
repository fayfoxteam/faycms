<?php
use fay\helpers\HtmlHelper;
use pharmrich\helpers\PostHelper;
use fay\services\file\FileService;
use fay\helpers\StringHelper;

$type = PostHelper::getType($data['cat_id']);
?>
<article class="cf">
	<header class="cf">
		<h1><?php echo HtmlHelper::link(str_ireplace($keywords, "<mark>{$keywords}</mark>", HtmlHelper::encode($data['title'])), array("$type/{$data['id']}"), array(
			'encode'=>false,
		))?></h1>
		<span class="post-meta">
			<?php echo date('d M Y', $data['publish_time'])?>
			/
			<?php echo HtmlHelper::link($data['cat_title'], array("$type/{$data['cat_alias']}"), array(
				'class'=>'fc-red',
			))?>
			/
			<span class="fc-red"><?php echo $data['views']?> Views</span>
		</span>
	</header>
	<?php if($data['thumbnail']){
		echo HtmlHelper::link(HtmlHelper::img($data['thumbnail'], FileService::PIC_RESIZE, array(
			'dw'=>300,
			'dh'=>230,
			'alt'=>HtmlHelper::encode($data['title']),
		)), array("$type/{$data['id']}"), array(
			'encode'=>false,
			'class'=>'thumbnail',
			'title'=>HtmlHelper::encode($data['title']),
		));
	}?>
	<p><?php echo nl2br(HtmlHelper::encode(StringHelper::niceShort($data['abstract'], 250)))?></p>
	<?php echo HtmlHelper::link('Read More', array("$type/{$data['id']}"), array(
		'class'=>'btn-red btn-sm mt20',
		'title'=>false,
	))?>
</article>