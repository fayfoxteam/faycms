<?php
use fay\helpers\Date;
?>
<form method="post" id="form">
	<div class="row">
		<div class="col-12">
			<div class="box">
				<div class="box-content">
					<p><strong>Current Timestamp</strong>: <em><?php echo F::app()->current_time?></em></p>
					<p><strong>Current Time</strong>: <?php echo Date::format(F::app()->current_time)?></p>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>Timestamp</h3></div>
				<div class="box-content">
					<?php echo F::form()->textarea('timestamps', array(
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
					<div style="min-height:200px"><?php if(F::app()->input->post('timestamps')){
						$timestamps = explode("\r\n", F::app()->input->post('timestamps'));
						foreach($timestamps as $t){
							echo date('Y-m-d H:i:s', intval($t)), '<br />';
						}
					}?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>Datetime</h3></div>
				<div class="box-content">
					<?php echo F::form()->textarea('datetimes', array(
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
					<div style="min-height:200px"><?php if(F::app()->input->post('datetimes')){
						$datetimes = explode("\r\n", F::app()->input->post('datetimes'));
						foreach($datetimes as $d){
							echo strtotime($d), '<br />';
						}
					}?></div>
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