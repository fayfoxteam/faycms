<?php
use fay\helpers\Html;
use fay\services\File;
?>
<?php foreach($posts as $post){?>
	<article>
		<header>
			<h2><?php echo Html::link($post['post']['title'], array('news/'.$post['post']['id']))?></h2>
			<div class="meta">
				<span>发布于：<?php echo $post['post']['format_publish_time']?></span>
				<span>阅读数：<?php echo $post['meta']['views']?></span>
			</div>
		</header>
		<?php if($post['post']['thumbnail']){?>
		<figure><?php
			echo Html::link(Html::img($post['post']['thumbnail']['id'], File::PIC_RESIZE, array(
				'dw'=>748,
				'dh'=>286,
				'alt'=>Html::encode($post['post']['title']),
			)), array('news/'.$post['post']['id']), array(
				'encode'=>false,
				'title'=>Html::encode($post['post']['title']),
			));
		?></figure>
		<?php }?>
		<div class="introtext">
			<?php echo Html::encode($post['post']['abstract'])?>
		</div>
		<?php echo Html::link('阅读全文', array('news/'.$post['post']['id']), array(
			'class'=>'more',
		))?>
	</article>
<?php }?>