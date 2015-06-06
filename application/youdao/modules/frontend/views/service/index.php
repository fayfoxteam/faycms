<?php
use fay\helpers\Html;
?>
<div id="page-item">
	<h2 align="center"><?php echo Html::encode($post['title'])?></h2>
	<p><br /></p>
	<?php echo $post['content']?>
</div>