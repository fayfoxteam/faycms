<?php
/**
 * @var $hour array
 */
?>
<div class="swiper-slide" id="arm-7">
	<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
	<div class="layer dadao"><img src="<?php echo $this->appAssets('images/arm/dadao.png')?>"></div>
	<div class="layer title"><img src="<?php echo $this->appAssets('images/arm/t3.png')?>"></div>
	<div class="layer description">
		<p class="center">有价值有深度的关公文化网络体验之旅</p>
		<p class="center">为实战体验做战争准备</p>
	</div>
</div>
<div class="swiper-slide <?php if(!$hour){echo 'set-hour-slide';}?>" id="arm-8">
	<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
	<div class="layer subtitle">排勤务</div>
	<?php if($hour){?>
		<a class="layer result fancybox-inline" href="#hour-dialog"><span class="hour"><?php echo $hour['name']?></span></a>
	<?php }else{?>
		<div class="layer qiantong shake"><img src="<?php echo $this->appAssets('images/arm/qiantong.png')?>"></div>
	<?php }?>
	<div class="layer description">
		<p>规则说明：</p>
		<p>根据古历每天分为十二个时辰，手机摇一摇自行确定时间。按规则每天报到，具体上岗时间可自行随时掌握，按规坚持方可有效晋升军职。</p>
	</div>
</div>