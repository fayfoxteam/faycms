<?php
?>
<div class="box" id="box-seo" data-name="seo">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>SEO优化</h4>
	</div>
	<div class="box-content">
		<div class="form-field pb0">
			<label for="seo-title" class="title pb0">标题（title）</label>
			<?php echo F::form()->inputText('seo_title', array('id'=>'seo-title', 'class'=>'full-width'))?>
		</div>
		<div class="form-field pb0">
			<label for="seo-keyword" class="title pb0">关键词（keyword）</label>
			<?php echo F::form()->inputText('seo_keywords', array('id'=>'seo-keywords', 'class'=>'full-width'))?>
		</div>
		<div class="form-field pb0">
			<label for="seo-description" class="title pb0">描述（description）</label>
			<?php echo F::form()->textarea('seo_description', array('id'=>'seo-description', 'class'=>'full-width h60'))?>
		</div>
	</div>
</div>