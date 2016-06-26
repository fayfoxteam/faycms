<?php
use fay\services\Option;
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
<div id="right-fix-toolbar">
	<?php F::widget()->area('right-fix-toolbar')?>
</div>
<a href="#" title="Back to top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
<?php echo Flash::get()?>