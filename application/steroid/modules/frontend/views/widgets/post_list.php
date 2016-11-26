<?php
use fay\helpers\Html;

/**
 * @var array $posts
 */

?>
<?php foreach($posts as $p){?>
	<article class="cf">
		<?php if($p['post']['thumbnail']['id']){?>
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
					<span>Reads</span>
					<a href="<?php echo $p['post']['link']?>"><?php
						echo $p['meta']['views']
					?></a>
				</span>
			</div>
			<div class="post-description">
				<p><?php echo \fay\helpers\StringHelper::niceShort($p['post']['abstract'], 150)?></p>
				<a href="<?php echo $p['post']['link']?>" class="post-link">Read More <span class="fa fa-angle-right"></span></a>
			</div>
		</div>
	</article>
<?php }?>