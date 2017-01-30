<?php
/**
 * @var $this \fay\core\View
 */
$this->appendCss($this->appStatic('css/arm.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<div class="swiper-slide" id="arm-1">
			<div class="layer index-title"><img src="<?php echo $this->appStatic('images/arm/index-title.png')?>"></div>
			<div class="layer index-description"><img src="<?php echo $this->appStatic('images/arm/index-description.png')?>"></div>
		</div>
		<div class="swiper-slide" id="arm-2">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/arm/brand.png')?>"></div>
			<div class="layer steps">
				<a href="<?php echo $this->url('arm/job')?>"><img src="<?php echo $this->appStatic('images/arm/s5.png')?>"></a>
				<a href="<?php echo $this->url('arm/info')?>"><img src="<?php echo $this->appStatic('images/arm/s4.png')?>"></a>
				<a href="<?php echo $this->url('arm/set-hour')?>"><img src="<?php echo $this->appStatic('images/arm/s3.png')?>"></a>
				<a href="<?php echo $this->url('arm/set-arm')?>"><img src="<?php echo $this->appStatic('images/arm/s2.png')?>"></a>
				<a href="<?php echo $this->url('arm/set-defence')?>"><img src="<?php echo $this->appStatic('images/arm/s1.png')?>"></a>
			</div>
			<div class="layer description">
				<p>体验规则：</p>
				<p>点击依序进行选择，以摇令定之；前四项确定后存入档案不可更改；第五项每天按规履行军职，最高可晋升至将军。</p>
			</div>
		</div>
	</div>
</div>