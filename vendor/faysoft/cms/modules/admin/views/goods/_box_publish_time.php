<div class="box" id="box-publish-time" data-name="publish_time">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>发布时间</h4>
	</div>
	<div class="box-content">
		<?php echo F::form()->inputText('publish_time', array('class'=>'form-control timepicker'))?>
		<p class="fc-grey mt5">留空默认为当前时间</p>
	</div>
</div>