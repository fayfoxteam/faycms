<?php
?>
<div class="box" id="box-publish-time" data-name="publish-time">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>发布时间</h4>
	</div>
	<div class="box-content">
		<?php echo F::form()->inputText('publish_time', array('class'=>'form-control timepicker'))?>
		<div class="fc-grey">默认为当前时间</div>
	</div>
</div>