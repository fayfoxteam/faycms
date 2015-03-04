<?php
?>
<form method="post" id="form">
	<?php echo F::form()->textarea('key', array(
		'class'=>'wp90 h200 autosize',
	));?>
	<div class="mt20">
		<a href="javascript:;" id="form-submit" class="btn-1">提交</a>
	</div>
</form>
<div class="mt20">
	<h3>执行结果</h3>
	<?php pr($result);?>
</div>