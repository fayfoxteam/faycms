<?php
use fay\helpers\HtmlHelper;
use fay\services\file\FileService;
?>
<?php foreach($posts as $post){?>
	<article>
		<header>
			<h2><?php echo HtmlHelper::link($post['post']['title'], array('news/'.$post['post']['id']))?></h2>
			<div class="meta">
				<span>发布于：<?php echo $post['post']['format_publish_time']?></span>
				<span>阅读数：<?php echo $post['meta']['views']?></span>
			</div>
		</header>
		<?php if($post['post']['thumbnail']){?>
		<figure><?php
			echo HtmlHelper::link(HtmlHelper::img($post['post']['thumbnail']['id'], FileService::PIC_RESIZE, array(
				'dw'=>748,
				'dh'=>286,
				'alt'=>HtmlHelper::encode($post['post']['title']),
			)), array('news/'.$post['post']['id']), array(
				'encode'=>false,
				'title'=>HtmlHelper::encode($post['post']['title']),
			));
		?></figure>
		<?php }?>
		<div class="introtext">
			<?php echo HtmlHelper::encode($post['post']['abstract'])?>
		</div>
		<?php echo HtmlHelper::link('阅读全文', array('news/'.$post['post']['id']), array(
			'class'=>'more',
		))?>
	</article>
<?php }?>