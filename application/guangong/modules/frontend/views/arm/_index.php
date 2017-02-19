<?php
/**
 * @var $this \fay\core\View
 */
$this->appendCss($this->appAssets('css/arm.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<div class="swiper-slide" id="arm-1">
			<div class="layer index-title"><img src="<?php echo $this->appAssets('images/arm/index-title.png')?>"></div>
			<div class="layer index-description"><img src="<?php echo $this->appAssets('images/arm/index-description.png')?>"></div>
		</div>
		<?php $this->renderPartial('_steps')?>
	</div>
</div>
<?php $this->renderPartial('_js')?>