<form method="post" id="form">
	<div class="row">
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>json_decode</h3></div>
				<div class="box-content">
					<?php echo F::form()->textarea('json', array(
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
					<div style="min-height:239px"><textarea class="form-control h200 autosize"><?php var_export(json_decode(F::input()->post('json'), true));?></textarea></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>json_encode</h3></div>
				<div class="box-content">
					<?php echo F::form()->textarea('array', array(
						'class'=>'form-control h200 autosize',
					));?>
					<a href="javascript:;" id="form-submit" class="btn mt5">提交</a>
					<span class="fc-grey">Type php array code here. eg:<code>array('hello')</code></span>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>Result</h3></div>
				<div class="box-content">
					<div style="min-height:239px"><textarea class="form-control h200 autosize"><?php echo json_encode(eval('return '.F::input()->post('array').';'));?></textarea></div>
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