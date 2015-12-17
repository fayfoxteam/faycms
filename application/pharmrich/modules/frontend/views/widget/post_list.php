<?php
use fay\helpers\Html;
use pharmrich\helpers\PostHelper;
use fay\models\File;
use fay\helpers\String;
?>
<div class="post-list">
<?php foreach($posts as $p){?>
	<?php $type = PostHelper::getType($p['post']['cat_id'])?>
	<article class="cf">
		<header class="cf">
			<h1><?php echo Html::link($p['post']['title'], array("{$type}/{$p['post']['id']}"))?></h1>
			<span class="post-meta">
				<?php echo date('d M Y', $p['post']['publish_time'])?>
				/
				<?php echo Html::link($type, array($type), array(
					'class'=>'fc-red',
				))?>
				/
				<span class="fc-red"><?php echo $p['post']['views']?> Views</span>
			</span>
		</header>
		<?php if($p['post']['thumbnail']){
			echo Html::link(Html::img($p['post']['thumbnail'], File::PIC_RESIZE, array(
				'dw'=>150,
				'dh'=>115,
				'alt'=>Html::encode($p['post']['title']),
			)), array("{$type}/{$p['post']['id']}"), array(
				'encode'=>false,
				'class'=>'thumbnail',
				'title'=>Html::encode($p['post']['title']),
			));
		}?>
		<p><?php echo nl2br(Html::encode(String::niceShort($p['post']['abstract'], 250)))?></p>
		<?php echo Html::link('Read More', array("{$type}/{$p['post']['id']}"), array(
			'class'=>'btn-red btn-sm mt20',
			'title'=>false,
		))?>
	</article>
<?php }?>
</div>