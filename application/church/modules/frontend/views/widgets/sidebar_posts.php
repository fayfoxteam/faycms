<?php
use fay\helpers\Html;

/**
 * @var array $config
 * @var array $posts
 */
?>
<div class="widget">
	<h5 class="widget-title"><?php echo Html::encode($config['title'])?></h5>
	<?php foreach($posts as $p){?>
		<article>
			<div class="post-thumb">
				<a href="<?php echo $p['post']['link']?>"><?php
					echo Html::img($p['post']['thumbnail']['thumbnail'])
				?></a>
			</div>
			<div class="post-container">
				<h5 class="post-title"><?php
					echo Html::link($p['post']['title'], $p['post']['link'])
				?></h5>
				<div class="post-meta">
					<?php echo Html::link($p['category']['title'], array('cat/'.$p['category']['alias']), array(
						'class'=>'post-meta-category',
					))?>
					<time class="post-meta-time"><?php echo $p['post']['format_publish_time']?></time>
				</div>
			</div>
		</article>
	<?php }?>
</div>