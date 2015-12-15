<?php
use fay\helpers\Html;
use fay\models\File;
use fay\helpers\Date;
?>
<section class="box" id="<?php echo $alias?>">
	<div class="box-title">
		<h2><?php echo $config['title']?></h2>
		<?php echo Html::link('More', array('cook-recipes'), array(
			'class'=>'more-link',
		))?>
	</div>
	<div class="box-content">
		<ul class="posts-carousel">
		<?php foreach($posts as $p){?>
			<li>
				<a href="<?php echo $p['url']?>">
					<span class="item-on-hover"></span>
					<?php echo Html::img($p['thumbnail'], File::PIC_RESIZE, array(
						'dw'=>300,
						'dh'=>245,
						'alt'=>Html::encode($p['title']),
					))?>
				</a>
				<div class="posts-carousel-details">
					<?php echo Html::link($p['title'], $p['url'], array(
						'wrapper'=>'h2',
					))?>
					<div class="carousel-meta">
						<span class="post-format"><i class="fa fa-pencil"></i></span>
						<span class="details">
							<?php echo Date::format($p['publish_time'])?>
							/
							<span class="views"><?php echo $p['views']?> Views</span>
						</span>
					</div>
					<p><?php echo Html::encode($p['abstract'])?></p>
				</div>
			</li>
		<?php }?>
		</ul>	
	</div>
</section>