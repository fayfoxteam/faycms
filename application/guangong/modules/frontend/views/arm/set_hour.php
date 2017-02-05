<?php
/**
 * @var $this \fay\core\View
 * @var $hour array
 */
$this->appendCss($this->appAssets('css/arm.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<?php $this->renderPartial('_steps')?>
		<div class="swiper-slide" id="arm-7">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
			<div class="layer dadao"><img src="<?php echo $this->appAssets('images/arm/dadao.png')?>"></div>
			<div class="layer title"><img src="<?php echo $this->appAssets('images/arm/t3.png')?>"></div>
			<div class="layer description">
				<p class="center">有价值有深度的关公文化网络体验之旅</p>
				<p class="center">为实战体验做战争准备</p>
			</div>
		</div>
		<div class="swiper-slide" id="arm-8">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
			<div class="layer subtitle">排勤务</div>
			<?php if($hour){?>
			<a class="layer result" href="#hour-dialog"><span class="hour"><?php echo $hour['name']?></span></a>
			<?php }else{?>
			<div class="layer qiantong"><img src="<?php echo $this->appAssets('images/arm/qiantong.png')?>"></div>
			<?php }?>
			<div class="layer description">
				<p>规则说明：</p>
				<p>根据古历每天分为十二个时辰，手机摇一摇自行确定时间。按规则每天报到，具体上岗时间可自行随时掌握，按规坚持方可有效晋升军职。</p>
			</div>
		</div>
		<?php $this->renderPartial('_steps')?>
	</div>
</div>
<div class="hide">
	<div id="hour-dialog" class="dialog">
		<div class="dialog-content">
			<div class="form-group">
				<div class="content" id="hour-name">
					<label class="label-title">古历时间</label>
					<?php echo $hour['name']?>
				</div>
			</div>
			<div class="form-group">
				<div class="content" id="hour-time">
					<label class="label-title">北京时间</label>
					<?php
						echo \fay\helpers\NumberHelper::toLength($hour['start_hour'], 2),
							'时至',
							\fay\helpers\NumberHelper::toLength($hour['end_hour'], 2),
							'时'
					?>
				</div>
			</div>
			<div class="form-group">
				<div class="content" id="hour-description">
					<label class="label-title">时辰详情</label>
					<?php echo $hour['description'], $hour['zodiac']?>
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
<?php $this->renderPartial('_js')?>
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/jquery.fancybox-1.3.4.css')?>">
<script type="text/javascript" src="<?php echo $this->assets('js/jquery.fancybox-1.3.4.pack.js')?>"></script>
<?php if(!$hour){?>
<script src="<?php echo $this->assets('faycms/js/faycms.shake.js')?>"></script>
<script>
	$.shake(function(){
		//摇一摇触发排勤务
		if(common.swiper.activeIndex == 2){
			$.ajax({
				'type': 'GET',
				'url': system.url('api/hour/set'),
				'dataType': 'json',
				'cache': false,
				'success': function(resp){
					if(resp.status){
						var $arm8 = $('#arm-8');
						$arm8.find('.qiantong').remove();
						$arm8.append('<div class="layer result"><span class="hour">'+resp.data.name+'</span></div>');
						$('#arm-8 .result').fancybox({
							'type': 'inline',
							'centerOnScroll': true,
							'padding': 0,
							'showCloseButton': false,
							'width': '80%'
						});
					}else{
						common.toast($resp.message, 'error');
					}
				}
			});
		}
	});
</script>
<?php }?>
<script>
	$('#arm-8 .result').fancybox({
		'type': 'inline',
		'centerOnScroll': true,
		'padding': 0,
		'showCloseButton': false,
		'width': '80%'
	});
</script>
