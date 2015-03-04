<div class="box">
	<div class="box-title">
		<h4>考试周期</h4>
	</div>
	<div class="box-content">
		<div class="form-field pb0">
			<label for="seo-title" class="title pb0">开始时间</label>
			<?php echo F::form()->inputText('start_time', array('class'=>'timepicker'))?>
			<div class="color-grey">在此时间后可参加考试(可留空)</div>
		</div>
		<div class="form-field pb0">
			<label for="seo-title" class="title pb0">结束时间</label>
			<?php echo F::form()->inputText('end_time', array('class'=>'timepicker'))?>
			<div class="color-grey">在此时间前可参加考试(可留空)</div>
		</div>
	</div>
</div>