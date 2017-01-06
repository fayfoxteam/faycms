<?php
use fay\helpers\HtmlHelper;
?>
<div class="box" id="advantage">
	<h3 class="box-title">
		<span><?php echo HtmlHelper::encode($title)?></span>
	</h3>
	<div class="box-content">
		<ul><?php foreach($data as $d){
			echo HtmlHelper::tag('li', array(), $d);
		}?></ul>
	</div>
</div>