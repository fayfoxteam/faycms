<?php
use fay\services\Post;
use fay\helpers\Date;
use fay\helpers\Html;

$post_cats = Post::service()->getCats($data['id']);
?>
<article class="post-list-item">
	<div class="post-title">
		<h1>
			<a href="<?php echo $this->url('post/'.$data['id'])?>"><?php echo Html::encode($data['title'])?></a>
		</h1>
		<span class="post-meta">
			发表于 
			<time><?php echo Date::format($data['publish_time'])?></time>
		</span>
		<div class="clear"></div>
	</div>
	<div class="post-content"><?php echo $data['abstract']?></div>
	<div class="post-tags">
		<?php
		echo Html::link('<span>#'.Html::encode($data['cat_title']).'</span>', array('cat/'.$data['cat_id']), array(
			'class'=>'post-type',
			'title'=>Html::encode($data['cat_title']),
			'encode'=>false,
		));
		foreach($post_cats as $pc){
			echo Html::link('<span>#'.Html::encode($pc['title']).'</span>', array('cat/'.$pc['id']), array(
				'class'=>'post-type',
				'title'=>Html::encode($pc['title']),
				'encode'=>false,
			));
		}
		echo Html::link('阅读全文', array('post/'.$data['id']), array(
			'class'=>'post-more-link',
		));
		?>
		<div class="clear"></div>
	</div>
</article>