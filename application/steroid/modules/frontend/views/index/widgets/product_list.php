<?php
/**
 * @var $config
 * @var $posts
 */
?>
<section class="section" id="section-products">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="description">
					<div class="title"><h2><?php echo \fay\helpers\Html::encode($config['title'])?></h2></div>
					<div class="subtitle">
						<p>Full Category: <?php F::widget()->load('widget-category-list')?></p>
						<p><a href="<?php echo $this->url('post')?>">Click and check full prices of steroid powders.</a></p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<ul class="product-list">
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
				</ul>
			</div>
		</div>
	</div>
</section>