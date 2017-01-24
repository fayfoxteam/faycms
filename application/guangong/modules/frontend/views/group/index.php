<?php
/**
 * @var $this \fay\core\View
 */
$this->appendCss($this->appStatic('css/group.css'));
?>
<div class="swiper-container groups">
	<div class="swiper-wrapper">
		<div class="swiper-slide" id="group-1">
			<div class="layer layer-1"><img src="<?php echo $this->appStatic('images/group/1.png')?>"></div>
		</div>
		<div class="swiper-slide" id="group-2">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/group/brand.png')?>"></div>
			<div class="layer steps">
				<div class="step">
					<span class="number">第五式</span>
					<span class="title">解密</span>
				</div>
				<div class="step">
					<span class="number">第四式</span>
					<span class="title">兰谱</span>
				</div>
				<div class="step">
					<span class="number">第三式</span>
					<span class="title">盟誓</span>
				</div>
				<div class="step">
					<span class="number">第二式</span>
					<span class="title">拜帖</span>
				</div>
				<div class="step">
					<span class="number">第一式</span>
					<span class="title">称谓</span>
				</div>
			</div>
			<div class="layer explain">
				<p>使用说明：</p>
				<p>根据您结义进程，点击即可选择。</p>
			</div>
		</div>
		<div class="swiper-slide" id="group-3">
			<div class="layer brand"><img src="<?php echo $this->appStatic('images/group/brand.png')?>"></div>
			<div class="layer" id="step">
				<span class="number">第一式</span>
				<span class="title">称谓</span>
			</div>
			<div class="layer guangong"><img src="<?php echo $this->appStatic('images/group/guangong.png')?>"></div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->appStatic('js/group.js')?>"></script>
<script>group.init();</script>