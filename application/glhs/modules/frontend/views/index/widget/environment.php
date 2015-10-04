<?php
use fay\helpers\Html;
?>
<div class="box" id="environment">
	<h3 class="box-title">
		<span>勾勒画室</span>
	</h3>
	<div class="box-content">
		<ul><?php foreach($data as $d){
			echo Html::tag('li', array(), array(
				array(
					'tag'=>'a',
					'href'=>'javascript:;',
					'text'=>$d['key'],
				),
				array(
					'tag'=>'p',
					'class'=>'hide',
					'text'=>$d['value'],
				),
			));
		}?></ul>
	</div>
</div>