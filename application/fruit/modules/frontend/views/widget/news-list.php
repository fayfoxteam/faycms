<?php
use fay\helpers\Html;
use fay\models\File;
?>
<?php foreach($posts as $post){?>
	<article>
		<header>
			<h2><?php echo Html::link($post['title'], array('news/'.$post['id']))?></h2>
			<div class="meta">
				<span>发布于：<?php echo $post['publish_format_time']?></span>
				<span>阅读数：<?php echo $post['views']?></span>
			</div>
		</header>
		<?php if($post['thumbnail']){?>
		<figure><?php
			echo Html::link(Html::img($post['thumbnail'], File::PIC_RESIZE, array(
				'dw'=>748,
				'dh'=>286,
				'alt'=>Html::encode($post['title']),
			)), array('news/'.$post['id']), array(
				'encode'=>false,
				'title'=>Html::encode($post['title']),
			));
		?></figure>
		<?php }?>
		<div class="introtext">
			<?php echo Html::encode($post['abstract'])?>
		</div>
		<?php echo Html::link('阅读全文', array('news/'.$post['id']), array(
			'class'=>'more',
		))?>
	</article>
<?php }?>