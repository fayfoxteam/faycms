<?php
use fay\helpers\HtmlHelper;
use fay\services\file\FileService;

/**
 * @var $widget
 * @var $posts array
 */
?>
<section class="box" id="<?php echo $widget->alias?>">
	<div class="box-title">
		<h2><?php echo $widget->config['title']?></h2>
		<?php echo HtmlHelper::link('More', array('cook-recipe'), array(
			'class'=>'more-link',
		))?>
	</div>
	<div class="box-content">
		<ul class="posts-carousel">
		<?php foreach($posts as $p){?>
			<li>
				<a href="<?php echo $p['post']['link']?>">
					<span class="item-on-hover"></span>
					<?php echo HtmlHelper::img($p['post']['thumbnail']['id'], FileService::PIC_RESIZE, array(
						'dw'=>300,
						'dh'=>245,
						'alt'=>HtmlHelper::encode($p['post']['title']),
					))?>
				</a>
				<div class="posts-carousel-details">
					<?php echo HtmlHelper::link($p['post']['title'], $p['post']['link'], array(
						'wrapper'=>'h2',
					))?>
					<div class="carousel-meta">
						<span class="post-format"><i class="fa fa-pencil"></i></span>
						<span class="details">
							<?php echo $p['post']['format_publish_time']?>
							/
							<span class="views"><?php echo $p['meta']['views']?> Views</span>
						</span>
					</div>
					<p><?php echo HtmlHelper::encode($p['post']['abstract'])?></p>
				</div>
			</li>
		<?php }?>
		</ul>
	</div>
</section>