<?php
/**
 * @var $this \fay\core\View
 */
$this->appendCss($this->appAssets('css/recruit.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<div class="swiper-slide" id="recruit-1"></div>
		<div class="swiper-slide" id="recruit-2">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/recruit/brand.png')?>"></div>
			<div class="layer text"><img src="<?php echo $this->appAssets('images/recruit/2.png')?>"></div>
		</div>
		<?php $this->renderPartial('_steps')?>
	</div>
</div>
<?php $this->renderPartial('_js')?>