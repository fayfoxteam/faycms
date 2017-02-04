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
<div class="hide">
	<div id="shengping-dialog" class="dialog">
		<div class="dialog-content">
			<img src="<?php echo $this->appAssets('images/recruit/shengping.png')?>">
		</div>
	</div>
</div>
<link type="text/css" rel="stylesheet" href="<?php echo $this->assets('css/jquery.fancybox-1.3.4.css')?>">
<script type="text/javascript" src="<?php echo $this->assets('js/jquery.fancybox-1.3.4.pack.js')?>"></script>
<script>
	$('#shengping-link').fancybox({
		'type': 'inline',
		'centerOnScroll': true,
		'padding': 0,
		'showCloseButton': false,
		'width': '80%'
	});
</script>