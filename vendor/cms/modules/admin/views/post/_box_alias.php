<?php
?>
<div class="box" id="box-alias" data-name="alias">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>别名</h4>
	</div>
	<div class="box-content">
		<?php echo F::form()->inputText('alias', array(
			'class'=>'form-control',
		))?>
		<p class="color-grey">别名不可包含特殊字符，可留空。</p>
	</div>
</div>