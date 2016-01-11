<?php
use fay\helpers\Html;
?>
<div class="widget pages">
	<h3><?php echo $config['title']?></h3>
	<ul><?php foreach($pages as $p){
		echo Html::link($p['title'], $p['link'], array(
			'wrapper'=>'li',
		));
	}?></ul>
</div>