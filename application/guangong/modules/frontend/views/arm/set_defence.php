<?php
/**
 * @var $this \fay\core\View
 */
$this->appendCss($this->appAssets('css/arm.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<?php $this->renderPartial('_steps')?>
		<div class="swiper-slide" id="arm-3">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
			<div class="layer dadao"><img src="<?php echo $this->appAssets('images/arm/dadao.png')?>"></div>
			<div class="layer title"><img src="<?php echo $this->appAssets('images/arm/t1.png')?>"></div>
			<div class="layer description">
				<p class="center">有价值有深度的关公文化网络体验之旅</p>
				<p class="center">为实战体验做战争准备</p>
			</div>
		</div>
		<div class="swiper-slide" id="arm-4">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
			<div class="layer subtitle">定防区</div>
			<div class="layer map"><img src="<?php echo $this->appAssets('images/arm/map.png')?>"></div>
			<div class="layer defence-text"><img src="<?php echo $this->appAssets('images/arm/defence-text.png')?>"></div>
			<div class="layer shake"><img src="<?php echo $this->appAssets('images/arm/shake.png')?>"></div>
			<div class="layer description">
				<p>体验说明：</p>
				<p>关羽军团所募兵员驻守防区采取随机分配的原则，由手机摇一摇自行确定，分别驻守南郡（湖北荆州）、武陵郡（湖南常德）、零陵郡（湖南永州）防区</p>
			</div>
		</div>
		<?php $this->renderPartial('_steps')?>
	</div>
</div>
<?php if(!$defence){?>
<script src="<?php echo $this->assets('faycms/js/faycms.shake.js')?>"></script>
<script>
	$.shake(function(){
		//摇一摇触发订防区
		if(common.swiper.activeIndex == 2){
			$.ajax({
				'type': 'GET',
				'url': system.url('api/arm/set'),
				'dataType': 'json',
				'cache': false,
				'success': function(resp){
					if(resp.status){
						var $arm6 = $('#arm-6');
						$arm6.find('.arms').remove();
						$arm6.find('.shake').remove();
						$arm6.append('<div class="layer result"><img src="'+resp.data.picture.url+'"></div>');
					}else{
						common.toast($resp.message, 'error');
					}
				}
			});
		}
	});
</script>
<?php }?>