<?php
use fay\helpers\Html;

/**
 * @var $links array
 * @var $widget \fay\widgets\friendlinks\controllers\IndexController
 */
?>
<div class="widget widget-friendlinks" id="<?php echo Html::encode($widget->alias)?>">
	<div class="widget-title">
		<h3><?php echo Html::encode($widget->config['title'])?></h3>
	</div>
	<div class="widget-content">
		<ul>
		<?php foreach($links as $l){?>
			<li><?php echo Html::link($l['title'], $l['url']);?></li>
		<?php }?>
		</ul>
	</div>
</div>