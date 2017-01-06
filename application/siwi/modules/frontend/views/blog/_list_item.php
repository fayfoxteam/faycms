<?php
use fay\services\FileService;
use fay\helpers\HtmlHelper;
use fay\helpers\StringHelper;
use fay\helpers\DateHelper;
?>
<article class="clearfix">
	<div class="thumbnail"><?php 
		if($data['thumbnail']){
			echo HtmlHelper::link(HtmlHelper::img($data['thumbnail'], FileService::PIC_RESIZE, array(
				'dw'=>300,
				'dh'=>230,
				'alt'=>HtmlHelper::encode($data['title']),
				'title'=>HtmlHelper::encode($data['title']),
			)), array('blog/'.$data['id']) ,array(
				'encode'=>false,
				'title'=>HtmlHelper::encode($data['title']),
			));
		}else{
			echo HtmlHelper::link("<img src='{$this->url()}images/no-image.jpg' width='300' height='230' />", array('blog/'.$data['id']) ,array(
				'encode'=>false,
				'title'=>HtmlHelper::encode($data['title']),
			));
		}
	?></div>
	<header>
		<h1><?php echo HtmlHelper::link(StringHelper::niceShort($data['title'], 38, true), array('blog/'.$data['id']), array(
			'title'=>HtmlHelper::encode($data['title']),
			'encode'=>false,
		))?></h1>
		<div class="meta">
			<span class="author">
				作者：
				<?php echo HtmlHelper::link($data['nickname'], array('u/'.$data['user_id']))?>
			</span>
			|
			<span class="date"><?php echo DateHelper::niceShort($data['publish_time'])?></span>
			|
			<span class="comment"><?php echo $data['comments']?> 评论</span>
		</div>
	</header>
	<div class="abstract"><?php echo HtmlHelper::encode($data['abstract'])?></div>
	<?php echo HtmlHelper::link('阅读全文', array('blog/'.$data['id']), array(
		'class'=>'btn-blue more-link',
	))?>
</article>