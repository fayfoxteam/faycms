<div class="box" id="box-props" data-name="props">
	<div class="box-title">
		<a class="tools remove" title="隐藏"></a>
		<h4>附加属性</h4>
	</div>
	<div class="box-content">
	<?php $this->renderPartial('prop/_edit', array(
		'prop_set'=>$prop_set,
	))?>
	</div>
</div>