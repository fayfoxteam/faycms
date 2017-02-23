<?php
/**
 * @var $this \fay\core\View
 * @var $defence array
 * @var $arm array
 * @var $hour array
 * @var $user_extra array
 */
$this->appendCss($this->appAssets('css/arm.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<div class="swiper-slide" id="arm-1">
			<div class="layer index-title"><img src="<?php echo $this->appAssets('images/arm/index-title.png')?>"></div>
			<div class="layer index-description"><img src="<?php echo $this->appAssets('images/arm/index-description.png')?>"></div>
		</div>
		<div class="swiper-slide" id="arm-2">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/arm/brand.png')?>"></div>
			<div class="layer steps">
				<?php if($hour){?>
					<a href="#10" class="swiper-to" data-slide="10"><img src="<?php echo $this->appAssets('images/arm/s5.png')?>"></a>
					<a href="#8" class="swiper-to" data-slide="8"><img src="<?php echo $this->appAssets('images/arm/s4.png')?>"></a>
					<a href="#6" class="swiper-to" data-slide="6"><img src="<?php echo $this->appAssets('images/arm/s3.png')?>"></a>
					<a href="#4" class="swiper-to" data-slide="4"><img src="<?php echo $this->appAssets('images/arm/s2.png')?>"></a>
					<a href="#2" class="swiper-to" data-slide="2"><img src="<?php echo $this->appAssets('images/arm/s1.png')?>"></a>
				<?php }else{?>
					<a href="#2" class="swiper-to" data-slide="2"><img src="<?php echo $this->appAssets('images/arm/s5.png')?>"></a>
					<a href="#2" class="swiper-to" data-slide="2"><img src="<?php echo $this->appAssets('images/arm/s4.png')?>"></a>
					<a href="#2" class="swiper-to" data-slide="2"><img src="<?php echo $this->appAssets('images/arm/s3.png')?>"></a>
					<a href="#2" class="swiper-to" data-slide="2"><img src="<?php echo $this->appAssets('images/arm/s2.png')?>"></a>
					<a href="#2" class="swiper-to" data-slide="2"><img src="<?php echo $this->appAssets('images/arm/s1.png')?>"></a>
				<?php }?>
			</div>
			<div class="layer description">
				<p>体验规则：</p>
				<p>点击依序进行选择，以摇令定之；前四项确定后存入档案不可更改；第五项每天按规履行军职，最高可晋升至将军。</p>
			</div>
		</div>
		<?php
			$this->renderPartial('_defence_slides', array(
				'defence'=>$defence,
				'user_extra'=>$user_extra,
			));
			$this->renderPartial('_arm_slides', array(
				'arm'=>$arm
			));
			$this->renderPartial('_hour_slides', array(
				'hour'=>$hour
			));
			$this->renderPartial('_info_slides');
			$this->renderPartial('_job_slides');
		?>
	</div>
</div>
<?php $this->renderPartial('_js')?>
<?php
	$this->renderPartial('_arm_dialog', array(
		'arm'=>$arm
	));
	$this->renderPartial('_hour_dialog', array(
		'hour'=>$hour
	));
	$this->renderPartial('_rank_dialog');
?>