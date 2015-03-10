<div class="box">
	<div class="box-title">
		<h4>Camera参数</h4>
	</div>
	<div class="box-content">
		<div class="form-field pb0 pt0">
			<label class="title pb0">高度（height）</label>
			<?php echo F::form('widget')->inputText('height', array(
				'class'=>'w100',
			), 450)?>
			<p class="color-grey">若不写单位，默认为像素（px）</p>
		</div>
		<div class="form-field pb0">
			<label class="title pb0">过渡动画时长（transPeriod）</label>
			<?php echo F::form('widget')->inputText('transPeriod', array(
				'class'=>'w100',
			), 800)?>
			<span class="color-grey">（单位：毫秒）</span>
		</div>
		<div class="form-field pb0">
			<label class="title pb0">播放间隔时长（time）</label>
			<?php echo F::form('widget')->inputText('time', array(
				'class'=>'w100',
			), 5000)?>
			<span class="color-grey">（单位：毫秒）</span>
		</div>
		<div class="form-field pb0">
			<label class="title pb0">切换效果（fx）</label>
			<?php echo F::form('widget')->select('fx', array(
				'random'=>'random(随机)',
				'simpleFade'=>'simpleFade',
				'curtainTopLeft'=>'curtainTopLeft',
				'curtainTopRight'=>'curtainTopRight',
				'curtainBottomLeft'=>'curtainBottomLeft',
				'curtainBottomRight'=>'curtainBottomRight',
				'curtainSliceLeft'=>'curtainSliceLeft',
				'curtainSliceRight'=>'curtainSliceRight',
				'blindCurtainTopLeft'=>'blindCurtainTopLeft',
				'blindCurtainTopRight'=>'blindCurtainTopRight',
				'blindCurtainBottomLeft'=>'blindCurtainBottomLeft',
				'blindCurtainBottomRight'=>'blindCurtainBottomRight',
				'blindCurtainSliceBottom'=>'blindCurtainSliceBottom',
				'blindCurtainSliceTop'=>'blindCurtainSliceTop',
				'stampede'=>'stampede',
				'mosaic'=>'mosaic',
				'mosaicReverse'=>'mosaicReverse',
				'mosaicRandom'=>'mosaicRandom',
				'mosaicSpiral'=>'mosaicSpiral',
				'mosaicSpiralReverse'=>'mosaicSpiralReverse',
				'topLeftBottomRight'=>'topLeftBottomRight',
				'bottomRightTopLeft'=>'bottomRightTopLeft',
				'bottomLeftTopRight'=>'bottomLeftTopRight',
				'bottomLeftTopRight'=>'bottomLeftTopRight',
			), array(), 'random')?>
		</div>
	</div>
</div>