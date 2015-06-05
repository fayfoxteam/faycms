<?php
?>
<fieldset class="form-field">
	<div class="title">
		<label>视频分享:</label>
		<span class="tip">如果你制作了视频还可以添加视频链接分享给大家</span>
	</div>
	<?php echo \F::form()->inputText('video', array(
		'class'=>'inputxt long',
	))?>
</fieldset>