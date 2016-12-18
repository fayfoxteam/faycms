<?php
use fay\helpers\Html;

/**
 * @var $posts array
 */
?>
<?php foreach($posts as $p){?>
<article class="post-list-item">
	<div class="post-title">
		<h1><?php
			echo Html::link($p['post']['title'], $p['post']['link'])
		?></h1>
		<?php if($p['post']['format_publish_time']){?>
		<span class="post-meta">
			发表于 
			<time><?php echo $p['post']['format_publish_time']?></time>
		</span>
		<?php }?>
	</div>
	<div class="post-content"><?php echo nl2br($p['post']['abstract'])?></div>
	<div class="post-tags"><?php
		echo Html::link('阅读全文', $p['post']['link'], array(
			'class'=>'post-more-link',
		));
	?></div>
</article>
<?php }?>