<?php
use fay\helpers\HtmlHelper;

/**
 * @var $widget \fay\widgets\category_pages\controllers\IndexController
 * @var $pages array
 */
?>
<div class="widget widget-category-pages" id="widget-<?php echo HtmlHelper::encode($widget->alias)?>">
	<div class="widget-title">
		<h3><?php echo HtmlHelper::encode($widget->config['title'])?></h3>
	</div>
	<div class="widget-content">
		<ul>
		<?php foreach($pages as $p){?>
			<li><?php
				echo HtmlHelper::link($p['title'], $p['link']);
			?></li>
		<?php }?>
		</ul>
	</div>
</div>