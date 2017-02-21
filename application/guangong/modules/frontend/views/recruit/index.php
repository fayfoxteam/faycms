<?php
/**
 * @var $this \fay\core\View
 * @var $user_extra array
 * @var $states array
 * @var $arm array
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
		<?php $this->renderPartial('_step1')?>
		<?php $this->renderPartial('_step2')?>
		<?php $this->renderPartial('_step3', array(
			'user'=>$user,
			'user_extra'=>$user_extra,
			'states'=>$states,
		))?>
		<?php $this->renderPartial('_step4', array(
			'user'=>$user,
			'arm'=>$arm,
		))?>
	</div>
</div>
<?php $this->renderPartial('_js')?>