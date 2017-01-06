<?php
use fay\helpers\Html;
use fay\services\FileService;

/**
 * @var $widget
 * @var $posts array
 */
?>
<section class="box" id="<?php echo $widget->alias?>">
	<div class="box-title">
		<h2><?php echo $widget->config['title']?></h2>
		<?php echo Html::link('More', array('product'), array(
			'class'=>'more-link',
		))?>
	</div>
	<div class="box-content">
		<ul class="products-carousel">
		<?php foreach($posts as $p){?>
			<li>
				<a href="<?php echo FileService::getUrl($p['post']['thumbnail']['id'])?>" title="<?php echo Html::encode($p['post']['title'])?>" data-lightbox="our-products">
					<span class="item-on-hover"><span class="hover-image"></span></span>
					<?php echo Html::img($p['post']['thumbnail']['id'], FileService::PIC_RESIZE, array(
						'dw'=>300,
						'dh'=>245,
						'alt'=>Html::encode($p['post']['title']),
					))?>
				</a>
				<div class="products-carousel-details">
					<?php echo Html::link($p['post']['title'], $p['post']['link'], array(
						'wrapper'=>'h3',
					))?>
				</div>
			</li>
		<?php }?>
		</ul>
	</div>
</section>