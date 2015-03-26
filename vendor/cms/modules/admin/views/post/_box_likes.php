<?php
?>
<div class="box" id="box-likes" data-name="likes">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>点赞数</h4>
	</div>
	<div class="box-content">
		<?php echo F::form()->inputText('likes', array(
			'class'=>'form-control mw150',
		))?>
		<p class="color-grey">设定初始值，后续会按实际情况增减。</p>
	</div>
</div>