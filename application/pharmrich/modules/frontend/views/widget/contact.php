<?php
use fay\helpers\Html;
?>
<div class="m-contact">
	<h3><?php echo Html::encode($title)?></h3>
	<ul><?php
		foreach($data as $d){
			echo Html::tag('li', array(), "{$d['key']}: {$d['value']}");
		}
	?></ul>
</div>