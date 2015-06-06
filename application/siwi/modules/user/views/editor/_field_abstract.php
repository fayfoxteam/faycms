<?php
?>
<fieldset class="form-field">
	<div class="title">
		<label class="title">介绍:</label>
		<span class="tip">与其他设计师分享你到设计灵感与思路</span>
	</div>
	<?php echo \F::form()->textarea('abstract', array(
		'class'=>'inputxt long',
		'style'=>'height:164px;',
	))?>
</fieldset>