<div class="box">
	<div class="box-title">
		<h4>参数</h4>
	</div>
	<div class="box-content">
		<div class="form-field pb0 pt0">
			<label class="title pb0">图片宽度（单位：px）</label>
			<?php echo F::form('widget')->inputText('width', array(
				'class'=>'form-control',
			))?>
			<span class="fc-grey">图片会根据指定宽度裁剪，留空则不裁剪</span>
		</div>
		<div class="form-field pb0">
			<label class="title pb0">图片高度（单位：px）</label>
			<?php echo F::form('widget')->inputText('height', array(
				'class'=>'form-control',
			))?>
			<span class="fc-grey">图片会根据指定高度裁剪，留空则不裁剪</span>
		</div>
	</div>
</div>