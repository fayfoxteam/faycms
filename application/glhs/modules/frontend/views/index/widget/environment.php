<?php
use fay\helpers\HtmlHelper;
?>
<div class="box" id="environment">
	<h3 class="box-title">
		<span><?php echo HtmlHelper::encode($title)?></span>
	</h3>
	<div class="box-content">
		<ul><?php foreach($data as $d){
			echo HtmlHelper::tag('li', array(), array(
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