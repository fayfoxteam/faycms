<?php
use fay\models\Option;
use fay\models\Analyst;
?>
<footer class="g-ft">
	<div class="w1000">
		<div class="ft-cp"><?php echo Option::get('site.copyright')?></div>
		<div class="ft-power">
			今日访问量：<span class="color-red"><?php echo Analyst::model()->getPV()?></span>
			总访问量：<span class="color-red"><?php echo Analyst::model()->getAllPV()?></span>
			技术支持：<a href="http://www.fayfox.com/" target="_blank">Fayfox</a>
		</div>
	</div>
</footer>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/analyst.min.js"></script>
<script>_fa.init();</script>