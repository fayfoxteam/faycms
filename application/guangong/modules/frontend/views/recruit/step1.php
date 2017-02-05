<?php
/**
 * @var $this \fay\core\View
 */
$this->appendCss($this->appAssets('css/recruit.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<?php $this->renderPartial('_steps')?>
		<div class="swiper-slide" id="recruit-4">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/recruit/brand.png')?>"></div>
			<div class="layer dadao"><img src="<?php echo $this->appAssets('images/recruit/dadao.png')?>"></div>
			<div class="layer title"><img src="<?php echo $this->appAssets('images/recruit/t1.png')?>"></div>
			<div class="layer description">
				<p>体验规则：</p>
				<p>凡入关羽军团者，请详读招募令并领会其意图。</p>
			</div>
		</div>
		<div class="swiper-slide" id="recruit-5">
			<div class="layer brand"><img src="<?php echo $this->appAssets('images/recruit/brand.png')?>"></div>
			<div class="layer zhaomuling"><img src="<?php echo $this->appAssets('images/recruit/zhaomuling.png')?>"></div>
			<div class="layer zhaomuling-text"><img src="<?php echo $this->appAssets('images/recruit/zhaomuling-text.png')?>"></div>
		</div>
		<div class="swiper-slide" id="recruit-6">
			<div class="layer yi"><img src="<?php echo $this->appAssets('images/recruit/yi.png')?>"></div>
			<div class="layer yi-text"><img src="<?php echo $this->appAssets('images/recruit/yi-text.png')?>"></div>
		</div>
		<?php $this->renderPartial('_steps')?>
	</div>
</div>
<?php $this->renderPartial('_js')?>