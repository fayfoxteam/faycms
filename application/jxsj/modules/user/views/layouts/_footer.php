<?php
use fay\services\Option;
use fay\services\Analyst;
?>
<footer class="g-ft">
	<div class="w1000">
		<div class="ft-cp"><?php echo Option::get('site:copyright')?></div>
		<div class="ft-power">
			今日访问量：<span class="color-red"><?php echo Analyst::service()->getPV()?></span>
			总访问量：<span class="color-red"><?php echo Analyst::service()->getAllPV()?></span>
			技术支持：<a href="http://www.fayfox.com/" target="_blank">Fayfox</a>
		</div>
	</div>
</footer>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/analyst.min.js')?>"></script>
<script>_fa.init();</script>