<?php
use fay\helpers\HtmlHelper;

/**
 * @var $widget
 * @var $pages array
 */
?>
<div class="widget pages">
	<h3><?php echo $widget->config['title']?></h3>
	<ul><?php foreach($pages as $p){
		echo HtmlHelper::link($p['title'], $p['link'], array(
			'wrapper'=>'li',
		));
	}?></ul>
</div>