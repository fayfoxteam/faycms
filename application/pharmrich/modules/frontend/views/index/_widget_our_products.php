<?php
use fay\helpers\Html;
use fay\models\File;
?>
<section class="box" id="<?php echo $alias?>">
	<div class="box-title">
		<h2><?php echo $config['title']?></h2>
		<?php echo Html::link('More', array('product'), array(
			'class'=>'more-link',
		))?>
	</div>
	<div class="box-content">
		<ul class="products-carousel">
		<?php foreach($posts as $p){?>
			<li>
				<a href="<?php echo File::getUrl($p['thumbnail'])?>" rel="prettyPhoto[pp_gal]" title="<?php echo Html::encode($p['title'])?>">
					<span class="item-on-hover"><span class="hover-image"></span></span>
					<?php echo Html::img($p['thumbnail'], File::PIC_RESIZE, array(
						'dw'=>300,
						'dh'=>245,
						'alt'=>Html::encode($p['title']),
					))?>
				</a>
				<div class="products-carousel-details">
					<?php echo Html::link($p['title'], $p['link'], array(
						'wrapper'=>'h3',
					))?>
				</div>
			</li>
		<?php }?>
		</ul>	
	</div>
</section>