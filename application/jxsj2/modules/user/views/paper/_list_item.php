<?php
use fay\helpers\Html;
?>
<li>
	<?php echo Html::link($data['title'], array('user/paper/item', array(
		'id'=>$data['id'],
	)))?>
</li>