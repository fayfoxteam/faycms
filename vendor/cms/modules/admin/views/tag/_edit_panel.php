<div class="form-field">
	<label class="title">名称<em class="required">*</em></label>
	<?php echo F::form()->inputText('title', array(
		'class'=>'form-control',
	))?>
</div>
<div class="form-field">
	<label class="title">SEO Title</label>
	<?php echo F::form()->inputText('seo_title', array(
		'class'=>'form-control',
	))?>
</div>
<div class="form-field">
	<label class="title">SEO Keywords</label>
	<?php echo F::form()->textarea('seo_keywords', array(
		'class'=>'form-control h30 autosize',
	))?>
</div>
<div class="form-field">
	<label class="title">SEO Description</label>
	<?php echo F::form()->textarea('seo_description', array(
		'class'=>'form-control h60 autosize',
	))?>
</div>