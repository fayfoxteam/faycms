<?php
/**
 * @var $this \fay\core\View
 * @var $defence array
 * @var $arm array
 * @var $hour array
 */
$this->appendCss($this->appAssets('css/arm.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<div class="swiper-slide" id="arm-1">
			<div class="layer index-title"><img src="<?php echo $this->appAssets('images/arm/index-title.png')?>"></div>
			<div class="layer index-description"><img src="<?php echo $this->appAssets('images/arm/index-description.png')?>"></div>
		</div>
		<?php
			$this->renderPartial('_steps');
			$this->renderPartial('_defence_slides', array(
				'defence'=>$defence
			));
			$this->renderPartial('_arm_slides', array(
				'arm'=>$arm
			));
			$this->renderPartial('_hour_slides', array(
				'hour'=>$hour
			));
		?>
		
	</div>
</div>
<?php $this->renderPartial('_js')?>
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/jquery.fancybox-1.3.4.css')?>">
<script type="text/javascript" src="<?php echo $this->assets('js/jquery.fancybox-1.3.4.pack.js')?>"></script>
<?php
	$this->renderPartial('_arm_dialog', array(
		'arm'=>$arm
	));
	$this->renderPartial('_hour_dialog', array(
		'hour'=>$hour
	));
?>