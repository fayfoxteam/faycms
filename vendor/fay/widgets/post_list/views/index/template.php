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
		<?php if(!empty($config['date_format'])){?>
		<span class="post-meta">
			发表于 
			<time><?php echo $config['date_format'] == 'pretty' ?
				Date::niceShort($p['publish_time']) : \date($config['date_format'], $p['publish_time'])?></time>
		</span>
		<?php }?>
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