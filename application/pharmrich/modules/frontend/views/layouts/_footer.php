<?php
use fay\models\Option;
use fay\models\Flash;
?>
<footer class="g-ft">
	<div class="centered-wrapper">
		<?php F::widget()->load('footer-about-us')?>
		<?php F::widget()->load('contact-1')?>
		<?php F::widget()->load('contact-2')?>
		<?php F::widget()->load('contact-3')?>
	</div>
	<div class="g-fcp">
		<div class="centered-wrapper">
			<p class="tip">最佳分辨率1280*800，建议使用Chrome、Firefox、Safari、ie10版本浏览器</p>
			<p class="cp"><?php echo Option::get('site:copyright')?></p>
		</div>
	</div>
</footer>
<?php echo Flash::get()?>