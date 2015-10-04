<?php
use fay\helpers\Html;
?>
<div class="box" id="advantage">
	<h3 class="box-title">
		<span>勾勒优势</span>
	</h3>
	<div class="box-content">
		<ul><?php foreach($data as $d){
			echo Html::tag('li', array(), $d);
		}?></ul>
	</div>
</div>