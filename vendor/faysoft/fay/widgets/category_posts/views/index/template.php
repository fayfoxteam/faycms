<?php
use fay\helpers\Html;

/**
 * @var $widget fay\widgets\category_posts\controllers\IndexController
 * @var $posts array
 */
?>
<div class="widget widget-category-posts" id="widget-<?php echo Html::encode($widget->alias)?>">
	<div class="widget-title">
		<h3><?php echo Html::encode($widget->config['title'])?></h3>
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