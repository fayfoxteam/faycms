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
					<a href="javascript:;" id="datetime-link" class="btn mt5">date('Y-m-d H:i:s')</a>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="box" id="datetime-result-box">
				<div class="box-title"><h3>Result</h3></div>
				<div class="box-content">
					<div style="min-height:239px"><?php echo F::form()->textarea('', array(
						'id'=>'datetime-result',
						'class'=>'form-control h200 autosize',
					));?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-6">
			<div class="box">
				<div class="box-title"><h3>Datetime</h3></div>
				<div class="box-content">
					<?php echo F::form()->textarea('dates', array(
						'class'=>'form-control h200 autosize',
					));?>
					<a href="javascript:;" id="strtotime-link" class="btn mt5">strtotime</a>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="box" id="strtotime-result-box">
				<div class="box-title"><h3>Result</h3></div>
				<div class="box-content">
					<div style="min-height:239px"><?php echo F::form()->textarea('', array(
						'id'=>'strtotime-result',
						'class'=>'form-control h200 autosize',
					));?></div>
				</div>
			</div>
		</div>
	</div>
</form>
<script>
$(function(){
	var toolsDate = {
		/**
		 * 日期转时间戳
		 */
		'strtotime': function(){
			$.ajax({
				type: 'POST',
				url: system.url('tools/function/strtotime'),
				data: {
					'dates': $('[name="dates"]').val()
				},
				beforeSend: function(){
					$('#strtotime-result-box').block();
				},
				success: function(resp){
					$('#strtotime-result-box').unblock();
					if(resp.status){
						$('#strtotime-result').val(resp.data.timestamps);
						autosize.update($('#strtotime-result'));
					}else{
						common.alert(resp.message);
					}
				}
			});
		},
		'datetime': function(){
			$.ajax({
				type: 'POST',
				url: system.url('tools/function/datetime'),
				data: {
					'timestamps': $('[name="timestamps"]').val()
				},
				beforeSend: function(){
					$('#datetime-result-box').block();
				},
				success: function(resp){
					$('#datetime-result-box').unblock();
					if(resp.status){
						$('#datetime-result').val(resp.data.dates);
						autosize.update($('#datetime-result'));
					}else{
						common.alert(resp.message);
					}
				}
			});
		},
		'events': function(){
			//日期转时间戳
			$('#strtotime-link').on('click', function(){
				toolsDate.strtotime();
			});
			
			$("[name='dates']").keydown(function(event){
				if((event.keyCode == 82 || event.keyCode == 83) && event.ctrlKey){
					toolsDate.strtotime();
					return false;
				}
			});
			
			//时间戳转日期
			$('#datetime-link').on('click', function(){
				toolsDate.datetime();
			});
			
			$("[name='timestamps']").keydown(function(event){
				if((event.keyCode == 82 || event.keyCode == 83) && event.ctrlKey){
					toolsDate.datetime();
					return false;
				}
			});
		},
		'init': function(){
			this.events();
		}
	};
	toolsDate.init();
});
</script>