<?php
use fay\services\OptionService;
?>
<footer id="colophon">
	<div id="footer-copyright-container">
		<div id="footer-copyright"><?php echo OptionService::get('site:copyright')?></div>
		<div id="footer-copyright-welcome">
			Welcome to join us.
			Power By <a href="http://www.siwi.me/">Siwi.Me</a>
		</div>
	</div>
</footer>
<script type="text/javascript" src="<?php echo $this->assets('faycms/js/analyst.min.js')?>"></script>
<script>_fa.init();</script>