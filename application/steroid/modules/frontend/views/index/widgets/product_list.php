<?php
/**
 * @var $posts
 */
?>
<?php foreach($posts as $p){?>
	<?php $props = \fay\helpers\ArrayHelper::column($p['props'], null, 'alias')?>
	<li>
		<div class="image">
			<a href="<?php echo $p['post']['link']?>">
				<span class="item-on-hover"><span class="hover-image"></span></span>
				<img src="<?php echo $p['post']['thumbnail']['thumbnail']?>">
			</a>
		</div>
		<div class="meta">
			<h3 class="title"><a href="<?php echo $p['post']['link']?>"><?php
				echo \fay\helpers\Html::encode($p['post']['title'])
			?></a></h3>
			<p class="price"><?php echo $props['price']['value']?></p>
			<p class="props">Melting point: 33-35°C.</p>
		</div>
	</li>
<?php }?>