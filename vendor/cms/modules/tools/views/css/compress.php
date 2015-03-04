<?php
use fay\helpers\Html;
?>
<h3>before</h3>
<form method="post" id="form">
<?php echo F::form()->textarea('data', array(
	'style'=>'width:95%;height:260px;',
));?>
<div class="margin-top-10">
	<a href="javascript:;" id="form-submit" class="btn-1">压缩</a>
</div>
</form>
<h3>after</h3>
<?php echo Html::textarea('after', $after_compress, array(
	'style'=>'width:95%;height:260px;',
))?>
