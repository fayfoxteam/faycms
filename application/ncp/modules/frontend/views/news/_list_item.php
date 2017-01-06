<?php
use fay\helpers\HtmlHelper;
use ncp\helpers\FriendlyLink;
use fay\helpers\DateHelper;
?>
<li>
	<div class="news_title">
		<h2><?php echo HtmlHelper::link($data['title'], FriendlyLink::getNewsLink(array(
			'id'=>$data['id'],
		)))?></h2>
		<span><?php echo DateHelper::niceShort($data['publish_time'])?></span>
	</div>     
	<div class="news_info"><?php echo HtmlHelper::encode($data['abstract'])?></div>
	<div class="news_view"><?php echo HtmlHelper::link('详情', FriendlyLink::getNewsLink(array(
		'id'=>$data['id'],
	)))?></div>
</li>