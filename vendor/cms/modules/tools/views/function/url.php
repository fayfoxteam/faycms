<?php
use fay\helpers\Html;
?>
<form method="post" id="form">
	<div class="row">
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>urlencode</h3></div>
				<div class="box-content">
					<?php echo F::form()->textarea('url', array(
						'class'=>'form-control h200 autosize',
					));?>
					<a href="javascript:;" id="form-submit" class="btn mt5">提交</a>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>Result</h3></div>
				<div class="box-content">
					<div style="min-height:239px"><?php
						echo Html::textarea('', urlencode(\F::input()->post('url')), array(
							'class'=>'form-control h200 autosize',
						));
					?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>urldecode</h3></div>
				<div class="box-content">
					<?php echo F::form()->textarea('encode_url', array(
						'class'=>'form-control h200 autosize',
					));?>
					<a href="javascript:;" id="form-submit" class="btn mt5">提交</a>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>Result</h3></div>
				<div class="box-content">
					<div style="min-height:239px"><?php
						echo Html::textarea('', urldecode(\F::input()->post('encode_url')), array(
							'class'=>'form-control h200 autosize',
						));
					?></div>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
$("[name='json'],[name='array']").keydown(function(event){
	if((event.keyCode == 82 || event.keyCode == 83) && event.ctrlKey){
		$("#form").submit();
		return false;
	}
});
</script>