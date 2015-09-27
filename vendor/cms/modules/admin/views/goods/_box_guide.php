<div class="box">
	<div class="box-title">
		<a class="tools toggle" title="点击以切换"></a>
		<h4>导购</h4>
	</div>
	<div class="box-content">
		<div class="misc-pub-section b0">
			<strong>排序</strong>
			<?php echo F::form()->inputText('sort', array(
				'class'=>'form-control w90 ib',
			), 10000)?>
			<span class="fc-grey">数字越小越靠前</span>
		</div>
		<div class="misc-pub-section">
			<strong>推荐</strong>
			<?php echo F::form()->inputCheckbox('is_new', 1, array(
				'label'=>'新品',
			))?>
			<?php echo F::form()->inputCheckbox('is_hot', 1, array(
				'label'=>'热销',
			))?>
		</div>
	</div>
</div>