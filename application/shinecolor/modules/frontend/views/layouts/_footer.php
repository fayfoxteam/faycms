<?php
use fay\services\OptionService;
?>
<footer id="footer">
	<div class="copy-right">
		<div class="w1000">
			<p class="cp"><?php echo OptionService::get(site.shine_color_copyright)?></p>
			<p class="beian"><?php echo OptionService::get(site.shine_color_beian)?>  技术支持：<a href="http://www.siwi.me" target="_blank">Siwi.Me</a></p>
		</div>
	</div>
</footer>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/analyst.min.js')?>"></script>
<script>_fa.init();</script>