<?php
use fay\helpers\HtmlHelper;
?>
<li>
	<?php echo HtmlHelper::link($data['title'], $data['url'], array(
		'target'=>'_blank',
	))?>
</li>