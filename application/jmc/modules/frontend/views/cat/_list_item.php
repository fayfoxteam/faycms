<?php

use fay\helpers\Html;
use fay\helpers\String;
// dump($data);
?>

<ul>
	<li><span><?php echo $index.'.';?></span></li>
	<li><p><?php echo Html::link($data['title'], array('post/'.$data['id']))?>
	       <?php echo String::niceShort($data['title'], 180)?>
	</p></li>
	<div class="clear"></div>
</ul>