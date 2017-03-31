<?php
use fay\helpers\HtmlHelper;
use fay\services\file\FileService;
?>
<section class="box" id="<?php echo $alias?>">
	<div class="box-title">
		<h2><?php echo $page['title']?></h2>
		<?php echo HtmlHelper::link('More', array($page['alias']), array(
			'class'=>'more-link',
		))?>
	</div>
	<div class="box-content"><?php
		if($page['thumbnail']){
			echo HtmlHelper::link(HtmlHelper::img($page['thumbnail'], FileService::PIC_RESIZE, array(
				'dw'=>156,
				'dh'=>110,
				'alt'=>HtmlHelper::encode($page['title']),
			)), array($page['alias']), array(
				'encode'=>false,
				'title'=>HtmlHelper::encode($page['title']),
			));
		}
		echo nl2br($page['abstract']);
	?></div>
</section>