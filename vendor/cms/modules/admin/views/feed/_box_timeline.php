<div class="box" id="box-timeline" data-name="timeline">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>时间轴</h4>
	</div>
	<div class="box-content">
		<?php echo F::form()->inputText('timeline', array('class'=>'form-control timepicker'))?>
		<p class="fc-grey">以时间轴的方式设置排序，若设为将来时间，则在这个时间到来前，该动态都将置顶。</p>
		<p class="fc-grey">留空默认为发布时间</p>
	</div>
</div>