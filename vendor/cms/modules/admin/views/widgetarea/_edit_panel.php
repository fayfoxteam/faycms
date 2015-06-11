<div class="form-field">
	<label class="title">别名<em class="required">*</em></label>
	<?php echo F::form()->inputText('alias', array(
		'class'=>'form-control',
	))?>
	<p class="fc-grey">前台通过别名来调用小工具域</p>
</div>
<div class="form-field">
	<label class="title">描述</label>
	<?php echo F::form()->textarea('description', array(
		'class'=>'form-control h90 autosize',
	))?>
</div>