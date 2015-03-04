<div class="form-field">
	<label>名称<em class="color-red">*</em></label>
	<?php echo F::form()->inputText('title', array(
		'class'=>'full-width',
	))?>
</div>
<div class="form-field">
	<label>SEO Title</label>
	<?php echo F::form()->inputText('seo_title', array(
		'class'=>'full-width',
	))?>
</div>
<div class="form-field">
	<label>SEO Keywords</label>
	<?php echo F::form()->textarea('seo_keywords', array(
		'class'=>'full-width h30 autosize',
	))?>
</div>
<div class="form-field">
	<label>SEO Description</label>
	<?php echo F::form()->textarea('seo_description', array(
		'class'=>'full-width h60 autosize',
	))?>
</div>