<div class="box">
	<div class="box-title">
		<h4>nivo.slider参数</h4>
	</div>
	<div class="box-content">
		<div class="form-field pb0 pt0">
			<label class="title pb0">外层元素ID（id）</label>
			<?php echo F::form('widget')->inputText('element_id', array(
				'class'=>'form-control',
			))?>
			<span class="fc-grey">用于定制式样等</span>
		</div>
		<div class="form-field pb0">
			<label class="title pb0">过渡动画时长（animSpeed）</label>
			<?php echo F::form('widget')->inputText('animSpeed', array(
				'class'=>'form-control',
			), 500)?>
			<span class="fc-grey">（单位：毫秒）</span>
		</div>
		<div class="form-field pb0">
			<label class="title pb0">停顿时长（pauseTime）</label>
			<?php echo F::form('widget')->inputText('pauseTime', array(
				'class'=>'form-control',
			), 5000)?>
			<span class="fc-grey">（单位：毫秒）</span>
		</div>
		<div class="form-field pb0">
			<label class="title pb0">切换效果（effect）</label>
			<?php echo F::form('widget')->select('effect', array(
				'random'=>'random(随机)',
				'sliceDown'=>'sliceDown',
				'sliceDownLeft'=>'sliceDownLeft',
				'sliceUp'=>'sliceUp',
				'sliceUpLeft'=>'sliceUpLeft',
				'sliceUpDown'=>'sliceUpDown',
				'sliceUpDownLeft'=>'sliceUpDownLeft',
				'fold'=>'fold',
				'fade'=>'fade',
				'slideInRight'=>'slideInRight',
				'slideInLeft'=>'slideInLeft',
				'boxRandom'=>'boxRandom',
				'boxRain'=>'boxRain',
				'boxRainReverse'=>'boxRainReverse',
				'boxRainGrow'=>'boxRainGrow',
				'boxRainGrowReverse'=>'boxRainGrowReverse',
			), array(
				'class'=>'form-control',
			), 'random')?>
		</div>
		<div class="form-field pb0">
			<label class="title pb0">是否显示左右导航（directionNav）</label>
			<?php echo F::form('widget')->inputRadio('directionNav', 1, array(
				'label'=>'是',
			), true)?>
			<?php echo F::form('widget')->inputRadio('directionNav', 0, array(
				'label'=>'否',
			))?>
		</div>
		<div class="form-field pb0">
			<label class="title pb0">图片宽度（单位：px）</label>
			<?php echo F::form('widget')->inputText('width', array(
				'class'=>'form-control',
			))?>
			<span class="fc-grey">图片会根据指定宽度裁剪，留空则不裁剪</span>
		</div>
		<div class="form-field pb0">
			<label class="title pb0">图片高度（单位：px）</label>
			<?php echo F::form('widget')->inputText('height', array(
				'class'=>'form-control',
			))?>
			<span class="fc-grey">图片会根据指定高度裁剪，留空则不裁剪</span>
		</div>
	</div>
</div>