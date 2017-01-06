<?php
use fay\helpers\Html;
use fay\services\FileService;

/**
 * @var array $posts
 */
?>
<?php foreach($posts as $p){?>
	<article>
		<?php if($p['files']){?>
		<div class="post-featured">
			<div class="swiper-container post-files">
				<div class="swiper-wrapper">
				<?php foreach($p['files'] as $file){?>
					<div class="swiper-slide">
						<?php echo Html::img($file['thumbnail'], FileService::PIC_ORIGINAL, array(
							'alt'=>$file['description'],
						))?>
					</div>
				<?php }?>
				</div>
				<div class="swiper-pagination"></div>
				<div class="swiper-control-container">
					<a class="swiper-btn-prev"></a>
					<a class="swiper-btn-next"></a>
				</div>
			</div>
		</div>
		<?php }?>
		<?php if(!$p['files'] && $p['post']['thumbnail']['id']){?>
		<div class="post-featured">
			<div class="post-thumb">
				<a href="<?php echo $p['post']['link']?>"><?php
					echo Html::img($p['post']['thumbnail']['thumbnail']);
				?></a>
			</div>
		</div>
		<?php }?>
		<div class="post-container">
			<h2 class="post-title">
				<a href="<?php echo $p['post']['link']?>"><?php
					echo Html::encode($p['post']['title'])
				?></a>
			</h2>
			<div class="post-meta">
				<time class="post-meta-item post-meta-time"><?php
					echo $p['post']['format_publish_time'];
				?></time>
				<?php echo Html::link($p['category']['title'], array('cat/'.$p['category']['id']), array(
					'class'=>array('post-meta-item', 'post-meta-category'),
				))?>
				<span class="post-meta-item post-meta-views">
					<span>阅读数</span>
					<a href="<?php echo $p['post']['link']?>"><?php
						echo $p['meta']['views']
					?></a>
				</span>
			</div>
			<div class="post-description">
				<p><?php echo $p['post']['abstract']?></p>
				<a href="<?php echo $p['post']['link']?>" class="btn btn-lg btn-blue">阅读全文</a>
			</div>
		</div>
	</article>
<?php }?>