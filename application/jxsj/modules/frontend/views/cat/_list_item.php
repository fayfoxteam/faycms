<?php
use fay\helpers\Html;
?>
<li>
	<?php echo Html::link($data['title'], array('post/'.$data['id']))?>
	<span class="time"><?php echo date('[Y-m-d]', $data['publish_time'])?></span>
</li>