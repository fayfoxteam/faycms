<?php
?>
<fieldset class="form-field">
	<div class="title">
		<label class="title">标签:</label>
		<span class="tip">填写或选择标签更容易被检索</span>
	</div>
	<?php echo \F::form()->inputText('tags', array(
		'class'=>'inputxt long',
	))?>
</fieldset>