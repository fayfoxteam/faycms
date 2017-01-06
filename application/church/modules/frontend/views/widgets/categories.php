<?php
use fay\helpers\HtmlHelper;

/**
 * @var $widget
 * @var $cats array
 */
?>
<div class="widget widget-categories">
	<h5 class="widget-title"><?php echo HtmlHelper::encode($widget->config['title'])?></h5>
	<ul>
	<?php foreach($cats as $c){?>
		<li>
			<?php echo HtmlHelper::link($c['title'], $c['link'])?>
			<span>(<?php echo $c['count']?>)</span>
		</li>
	<?php }?>
	</ul>
</div>