<?php
?>
<fieldset class="form-field">
	<div class="title">
		<label>标题:</label>
		<span class="tip">给它起一个响亮的标题吧</span>
	</div>
	<?php echo \F::form()->inputText('title', array(
		'class'=>'inputxt long',
		'ignore'=>false,
	))?>
</fieldset>