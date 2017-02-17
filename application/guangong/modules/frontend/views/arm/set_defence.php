<?php
/**
 * @var $this \fay\core\View
 * @var $defence array
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
			<?php if($defence){?>
			<div class="layer next-link"><?php
				echo \fay\helpers\HtmlHelper::link('选兵种', array('arm/set-arm#1'), array(
					'class'=>'btn-1',
				));
			?></div>
			<?php }else{?>
			<div class="layer shake"><img src="<?php echo $this->appAssets('images/arm/shake.png')?>"></div>
			<?php }?>
			<div class="layer description">
				<p>体验说明：</p>
				<p>关羽军团所募兵员驻守防区采取随机分配的原则，由手机摇一摇自行确定，分别驻守南郡（湖北荆州）、武陵郡（湖南常德）、零陵郡（湖南永州）防区</p>
			</div>
		</div>
		<?php $this->renderPartial('_steps')?>
	</div>
</div>
<?php $this->renderPartial('_js')?>
<?php if(!$defence){?>
<script src="<?php echo $this->assets('faycms/js/faycms.shake.js')?>"></script>
<script>
	$.shake(function(){
		//摇一摇触发定防区
		document.getElementById('shake-music').play();
		if(arm.enableShake && common.swiper.activeIndex == 2) {
			arm.setDefenceArea();
		}
	});
	$('.shake').on('click', function(){
		document.getElementById('shake-music').play();
		arm.setDefenceArea();
	});
</script>
<?php }?>
<audio src="<?php echo $this->appAssets('music/5018.wav')?>" id="shake-music" preload=""></audio>