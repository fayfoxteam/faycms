<?php
use fay\helpers\HtmlHelper;

/**
 * @var $widget array
 * @var $posts array
 */
?>
<div class="widget">
	<h5 class="widget-title"><?php echo HtmlHelper::encode($widget->config['title'])?></h5>
	<?php foreach($posts as $p){?>
		<article>
			<div class="post-thumb">
				<a href="<?php echo $p['post']['link']?>"><?php
					echo HtmlHelper::img($p['post']['thumbnail']['thumbnail'])
				?></a>
			</div>
			<div class="post-container">
				<h5 class="post-title"><?php
					echo HtmlHelper::link($p['post']['title'], $p['post']['link'])
				?></h5>
				<div class="post-meta">
					<?php echo HtmlHelper::link($p['category']['title'], array('cat/'.$p['category']['id']), array(
						'class'=>'post-meta-category',
					))?>
					<time class="post-meta-time"><?php echo $p['post']['format_publish_time']?></time>
				</div>
			</div>
		</article>
	<?php }?>
</div>