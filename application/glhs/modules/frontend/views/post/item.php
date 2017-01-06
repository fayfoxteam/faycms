<?php
use fay\helpers\HtmlHelper;
?>
<div class="container">
	<div class="g-mn">
		<h1 class="sec-title"><span><?php echo HtmlHelper::encode($post['post']['title'])?></span></h1>
		
		<div id="contact-page" class="clearfix">
			<?php echo $post['post']['content']?>
		</div>
	</div>
</div>