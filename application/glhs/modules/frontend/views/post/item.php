<?php
use fay\helpers\Html;
?>
<div class="container">
	<div class="g-mn">
		<h1 class="sec-title"><span><?php echo Html::encode($post['title'])?></span></h1>
		
		<div id="contact-page" class="clearfix">
			<?php echo $post['content']?>
		</div>
	</div>
</div>