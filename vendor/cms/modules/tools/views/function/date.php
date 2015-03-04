<?php
use fay\helpers\Date;
?>
<form method="post" id="form">
	<?php echo F::form()->textarea('key', array(
		'class'=>'wp90 h200 autosize',
	));?>
	<div class="mt20">
		<p>当前时间戳：<em><?php echo F::app()->current_time?></em></p>
		<p>当前时间：<?php echo Date::format(F::app()->current_time)?></p>
	</div>
	<div class="mt20">
		<a href="javascript:;" id="form-submit" class="btn-1">提交</a>
	</div>
</form>
<div class="mt20">
	<h3>执行结果</h3>
	<?php if(F::app()->input->post('key')){
		$timestamps = explode("\r\n", F::app()->input->post('key'));
		foreach($timestamps as $t){
			echo date('Y-m-d H:i:s', intval($t)), '<br />';
		}
	}?>
</div>