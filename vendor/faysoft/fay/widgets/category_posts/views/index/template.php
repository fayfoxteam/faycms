<?php
use fay\helpers\Html;

/**
 * @var string $alias
 * @var array $config
 * @var array $posts
 */
?>
<div class="widget widget-category-posts" id="widget-<?php echo Html::encode($alias)?>">
	<div class="widget-title">
		<h3><?php echo Html::encode($config['title'])?></h3>
	</div>
	<div class="widget-content">
		<ul>
		<?php foreach($posts as $p){?>
			<li><?php
				echo Html::link($p['post']['title'], $p['post']['link']);
				echo $p['post']['format_publish_time'];
			?></li>
		<?php }?>
		</ul>
	</div>
</div>