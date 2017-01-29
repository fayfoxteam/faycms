<?php
/**
 * @var $this \fay\core\View
 * @var $hour array
 */
$this->appendCss($this->appStatic('css/arm.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<div class="swiper-slide" id="arm-7">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/arm/brand.png')?>"></div>
			<div class="layer dadao"><img src="<?php echo $this->appStatic('images/arm/dadao.png')?>"></div>
			<div class="layer title"><img src="<?php echo $this->appStatic('images/arm/t3.png')?>"></div>
			<div class="layer description">
				<p class="center">有价值有深度的关公文化网络体验之旅</p>
				<p class="center">为实战体验做战争准备</p>
			</div>
		</div>
		<div class="swiper-slide" id="arm-8">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/arm/brand.png')?>"></div>
			<div class="layer subtitle">排勤务</div>
			<?php if($hour){?>
			<div class="layer result"><span class="hour"><?php echo $hour['name']?></span></div>
			<?php }else{?>
			<div class="layer qiantong"><img src="<?php echo $this->appStatic('images/arm/qiantong.png')?>"></div>
			<?php }?>
			<div class="layer description">
				<p>规则说明：</p>
				<p>根据古历每天分为十二个时辰，手机摇一摇自行确定时间。按规则每天报到，具体上岗时间可自行随时掌握，按规坚持方可有效晋升军职。</p>
			</div>
		</div>
	</div>
</div>
<?php if(!$hour){?>
<script src="<?php echo $this->assets('faycms/js/faycms.shake.js')?>"></script>
<script>
	$.shake(function(){
		//摇一摇触发排勤务
		if(common.swiper.activeIndex == 1){
			$.ajax({
				'type': 'GET',
				'url': system.url('api/hour/set'),
				'dataType': 'json',
				'cache': false,
				'success': function(resp){
					if(resp.status){
						var $arm8 = $('#arm-8');
						$arm8.find('.qiantong').remove();
						$arm8.append('<div class="layer result"><span class="hour">'+resp.data.name+'</span></div>')
					}else{
						common.toast($resp.message, 'error');
					}
				}
			});
		}
	});
</script>
<?php }?>