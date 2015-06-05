<?php
use fay\helpers\Html;
use fay\models\File;
use ncp\helpers\FriendlyLink;
?>
<ul>
<?php foreach($posts as $p){?>
	<li><?php echo Html::link(Html::img($p['thumbnail'], File::PIC_RESIZE, array(
		'dw'=>220,
		'dh'=>120,
		'alt'=>Html::encode($p['title']),
	)), FriendlyLink::getSpecialLink(array(
		'id'=>$p['id'],
	)), array(
		'encode'=>false,
		'title'=>Html::encode($p['title']),
	))?></li>
<?php }?>
</ul>