<?php
use fay\helpers\Html;

/**
 * @var $widget
 */
?>
<div class="m-contact">
	<h3><?php echo Html::encode($widget->config['title'])?></h3>
	<ul><?php
		foreach($widget->config['data'] as $d){
			echo Html::tag('li', array(), "<span>{$d['key']}</span>: {$d['value']}");
		}
	?></ul>
</div>