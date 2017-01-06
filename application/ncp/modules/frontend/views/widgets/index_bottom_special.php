<?php
use fay\helpers\HtmlHelper;
use fay\services\FileService;
use ncp\helpers\FriendlyLink;
?>
<ul>
<?php foreach($posts as $p){?>
	<li><?php echo HtmlHelper::link(HtmlHelper::img($p['thumbnail'], FileService::PIC_RESIZE, array(
		'dw'=>220,
		'dh'=>120,
		'alt'=>HtmlHelper::encode($p['title']),
	)), FriendlyLink::getSpecialLink(array(
		'id'=>$p['id'],
	)), array(
		'encode'=>false,
		'title'=>HtmlHelper::encode($p['title']),
	))?></li>
<?php }?>
</ul>