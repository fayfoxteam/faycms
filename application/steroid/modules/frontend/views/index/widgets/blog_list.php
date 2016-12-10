<?php
/**
 * @var $config
 * @var $posts
 */

//获取分类描述
$cat = \fay\services\Category::service()->get($config['cat_id'], 'description');
?>
<section class="section" id="section-blog">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="title-group">
					<h2 class="title"><?php echo \fay\helpers\Html::encode($config['title'])?></h2>
					<div class="description">
						<p><?php echo \fay\helpers\Html::encode($cat['description'])?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="row post-list">
			<div class="col-md-12">
			<?php foreach($posts as $p){?>
				<article class="cf">
					<div class="image-container">
						<a href="<?php echo $p['post']['link']?>"><img src="<?php echo $p['post']['thumbnail']['thumbnail']?>" width="80" height="80"></a>
					</div>
					<div class="post-info">
						<h3 class="title"><a href="<?php echo $p['post']['link']?>"><?php echo \fay\helpers\Html::encode($p['post']['title'])?></a></h3>
						<h6 class="description"><?php echo $p['post']['format_publish_time']?> · Buy Anavar Powder,Oxandrolone Powder,Oral Steroid Powder</h6>
						<p class="abstract"><?php echo \fay\helpers\Html::encode($p['post']['abstract'])?></p>
					</div>
				</article>
			<?php }?>
			</div>
		</div>
	</div>
</section>