<?php
/**
 * @var $hour array
 */
?>
<div class="hide">
	<div id="hour-dialog" class="dialog">
		<div class="dialog-content">
			<div class="form-group">
				<div class="content">
					<label class="label-title">古历时间</label>
					<span id="hour-name"><?php echo $hour ? $hour['name']: ''?></span>
				</div>
			</div>
			<div class="form-group">
				<div class="content">
					<label class="label-title">北京时间</label>
					<span id="hour-time"><?php
					if($hour){
						echo \fay\helpers\NumberHelper::toLength($hour['start_hour'], 2),
						'时至',
						\fay\helpers\NumberHelper::toLength($hour['end_hour'], 2),
						'时';
					}
					?></span>
				</div>
			</div>
			<div class="form-group">
				<div class="content">
					<label class="label-title">时辰详情</label>
					<span id="hour-description"><?php if($hour){
						echo $hour['description'], $hour['zodiac'];
					}?></span>
				</div>
			</div>
			<div class="form-group">
				<div class="content" id="hour-standard">
					<label class="label-title">值勤规定</label>
					值勤时间一经确定将计入档案，需按规则每天报到，方可有效晋升军职。具体值勤报到时间自行掌握。
				</div>
			</div>
			<p class="bottom-description">不想当将军的士兵不是好士兵。</p>
		</div>
	</div>
</div>