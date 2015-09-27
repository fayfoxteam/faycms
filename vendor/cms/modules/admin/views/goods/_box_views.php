<?php
?>
<div class="box" id="box-views" data-name="views">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>阅读数</h4>
	</div>
	<div class="box-content">
		<?php echo F::form()->inputText('views', array(
			'class'=>'form-control mw150',
		))?>
		<p class="fc-grey">设定初始值，会按实际PV递增。</p>
	</div>
</div>