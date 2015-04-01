<?php
use fay\models\Option;
?>
<footer class="main-footer">
	<div class="footer-inner">
		<div class="fr go-up">
			<a href="#" rel="go-top"><i class="icon-angle-up"></i></a>
		</div>
		<div class="copyright">
			<?php echo Option::get('copyright')?>
		</div>
	</div>
</footer>