<div class="form-field">
	<label class="title">键<em class="required">*</em></label>
	<?php echo F::form()->inputText('option_name', array(
		'class'=>'form-control',
	))?>
</div>
<div class="form-field">
	<label class="title">值</label>
	<?php echo F::form()->textarea('option_value', array(
		'class'=>'form-control h90 autosize',
	))?>
</div>
<div class="form-field">
	<label class="title">描述</label>
	<?php echo F::form()->textarea('description', array(
		'class'=>'form-control h90 autosize',
	))?>
</div>