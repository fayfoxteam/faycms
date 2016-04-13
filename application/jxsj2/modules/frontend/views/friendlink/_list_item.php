<?php
use fay\helpers\Html;
?>
<li>
	<?php echo Html::link($data['title'], $data['url'], array(
		'target'=>'_blank',
	))?>
</li>