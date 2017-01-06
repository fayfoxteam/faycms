<?php
use fay\helpers\HtmlHelper;
?>
<li>
	<?php echo HtmlHelper::link($data['title'], array('user/paper/item', array(
		'id'=>$data['id'],
	)))?>
</li>