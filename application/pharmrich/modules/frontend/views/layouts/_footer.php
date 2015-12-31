<?php
use fay\models\Option;
use fay\models\Flash;
?>
<footer class="g-ft">
	<div class="centered-wrapper cf">
		<div class="ft-left"><?php F::widget()->load('footer-about-us')?></div>
		<div class="ft-right">
			<?php F::widget()->area('footer-contact')?>
		</div>
	</div>
	<div class="g-fcp">
		<div class="centered-wrapper">
			<p class="tip"></p>
			<p class="cp"><?php echo Option::get('site:copyright')?></p>
		</div>
	</div>
</footer>
<?php echo Flash::get()?>