<form method="get" id="form">
	<div class="row">
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>Content</h3></div>
				<div class="box-content">
					<?php echo F::form()->textarea('key', array(
						'class'=>'form-control h200 autosize',
					));?>
					<a href="javascript:;" id="form-submit" class="btn mt5">提交</a>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>Qr Code</h3></div>
				<div class="box-content">
				<?php if(F::input()->get('key')){?>
					<img src="<?php echo $this->url('file/qrcode', array(
						'data'=>F::input()->get('key', 'base64_encode'),
					))?>">
				<?php }?>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
$("[name='key']").keydown(function(event){
	if((event.keyCode == 82 || event.keyCode == 83) && event.ctrlKey){
		$("#form").submit();
		return false;
	}
});
</script>