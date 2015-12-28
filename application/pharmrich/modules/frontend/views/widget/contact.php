<?php
use fay\helpers\Html;
?>
<div class="m-contact">
	<h3><?php echo Html::encode($title)?></h3>
	<ul><?php
		foreach($data as $d){
			echo Html::tag('li', array(), "<span>{$d['key']}</span>: {$d['value']}");
		}
	?></ul>
</div>