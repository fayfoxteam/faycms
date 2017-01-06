<?php
use fay\helpers\Html;
use pharmrich\helpers\PostHelper;
use fay\services\FileService;
use fay\helpers\StringHelper;

/**
 * @var array $posts
 */
?>
<div class="post-list">
<?php foreach($posts as $p){?>
	<?php $type = PostHelper::getType($p['post']['cat_id'])?>
	<article class="cf">
		<header class="cf">
			<h1><?php echo Html::link($p['post']['title'], $p['post']['link'])?></h1>
			<span class="post-meta">
				<?php echo $p['post']['format_publish_time']?>
				/
				<?php echo Html::link($p['category']['title'], array("$type/{$p['category']['alias']}"), array(
					'class'=>'fc-red',
				))?>
				/
				<span class="fc-red"><?php echo $p['meta']['views']?> Views</span>
			</span>
		</header>
		<?php if($p['post']['thumbnail']['id']){
			echo Html::link(Html::img($p['post']['thumbnail']['id'], FileService::PIC_RESIZE, array(
				'dw'=>300,
				'dh'=>230,
				'alt'=>Html::encode($p['post']['title']),
			)), $p['post']['link'], array(
				'encode'=>false,
				'class'=>'thumbnail',
				'title'=>Html::encode($p['post']['title']),
			));
		}?>
		<p><?php echo nl2br(Html::encode(StringHelper::niceShort($p['post']['abstract'], 250)))?></p>
		<?php echo Html::link('Read More', $p['post']['link'], array(
			'class'=>'btn-red btn-sm mt20',
			'title'=>false,
		))?>
	</article>
<?php }?>
</div>