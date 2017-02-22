<?php
/**
 * @var $defence array
 * @var $user_extra array
 */
?>
<div class="swiper-slide" id="arm-3">
	<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
	<div class="layer dadao"><img src="<?php echo $this->appAssets('images/arm/dadao.png')?>"></div>
	<div class="layer title"><img src="<?php echo $this->appAssets('images/arm/t1.png')?>"></div>
	<div class="layer description">
		<p class="center">有价值有深度的关公文化网络体验之旅</p>
		<p class="center">为实战体验做战争准备</p>
	</div>
</div>
<div class="swiper-slide <?php if(!$defence){echo 'set-defence-slide stop-to-next';}?>" id="arm-4">
	<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
	<div class="layer subtitle">定防区</div>
	<div class="layer defence-text"><img src="<?php echo $this->appAssets('images/arm/defence-text.png')?>"></div>
	<?php if($user_extra){?>
		<div class="layer map"><img src="<?php echo $this->appAssets('images/arm/map.png')?>"></div>
		<?php if(!$defence){?>
			<div class="layer shake"><img src="<?php echo $this->appAssets('images/arm/shake.png')?>"></div>
		<?php }?>
	<?php }else{?>
		<div class="layer go-to-sign">您尚未加入关羽军团<br>请前往<a href="<?php echo $this->url('recruit')?>">天下招募令</a>参加招募</div>
	<?php }?>
	<div class="layer description">
		<p>体验说明：</p>
		<p>关羽军团所募兵员驻守防区采取随机分配的原则，由手机摇一摇自行确定，分别驻守南郡（湖北荆州）、武陵郡（湖南常德）、零陵郡（湖南永州）防区</p>
	</div>
</div>