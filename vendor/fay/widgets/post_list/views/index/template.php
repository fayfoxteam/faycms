<?php
use fay\helpers\Html;
use fay\helpers\Date;
?>
<?php foreach($posts as $p){?>
<article class="post-list-item">
	<div class="post-title">
		<h1><?php
			echo Html::link($p['title'], array(str_replace('{$id}', $p['id'], $config['uri'])));
		?></h1>
		<span class="post-meta">
			发表于 
			<time><?php echo Date::format($p['publish_time'])?></time>
		</span>
	</div>
	<div class="post-content"><?php echo nl2br($p['abstract'])?></div>
	<div class="post-tags">
		<?php
		echo Html::link($p['cat']['title'], array('cat/'.$p['cat_id']), array(
			'class'=>'post-type',
		));
		
		echo Html::link('阅读全文', array('post/'.$p['id']), array(
			'class'=>'post-more-link',
		));
		?>
	</div>
</article>
<?php }?>