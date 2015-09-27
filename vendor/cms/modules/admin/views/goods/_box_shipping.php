<div class="box">
	<div class="box-title">
		<a class="tools toggle" title="点击以切换"></a>
		<h4>物流参数</h4>
	</div>
	<div class="box-content">
		<div class="misc-pub-section b0">
			<strong>重量</strong>
			<?php echo F::form()->inputText('weight', array(
				'class'=>'form-control w70 ib',
			))?>
			<span class="fc-grey">单位：kg</span>
		</div>
		<div class="misc-pub-section">
			<strong>体积</strong>
			<?php echo F::form()->inputText('size', array(
				'class'=>'form-control w70 ib',
			))?>
			<span class="fc-grey">单位：立方米</span>
		</div>
	</div>
</div>