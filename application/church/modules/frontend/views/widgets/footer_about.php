<?php
use fay\helpers\Html;
?>
<aside class="col-md-5 col-sm-6">
	<h4><?php echo Html::encode($page['title'])?></h4>
	<div>
		<p><?php echo Html::encode($page['abstract'])?></p>
	</div>
</aside>