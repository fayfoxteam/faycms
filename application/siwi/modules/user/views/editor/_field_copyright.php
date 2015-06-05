<?php
?>
<fieldset class="form-field">
	<div class="title">
		<label class="title">版权:</label>
		<span class="tip">附上版权尊重原作者也是给自己的保障</span>
	</div>
	<?php echo \F::form()->inputText('copyright', array(
		'class'=>'inputxt long',
	), '网络分享，仅供学习与交流，版权为原作者所有。')?>
</fieldset>
