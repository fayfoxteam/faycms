<?php
use fay\helpers\HtmlHelper;
use fay\services\FileService;
use ncp\helpers\FriendlyLink;
?>
<li>
	<div class="sp_img flrwyj">
		<?php echo HtmlHelper::link(HtmlHelper::img($data['thumbnail'], FileService::PIC_RESIZE, array(
			'dw'=>400,
			'dh'=>284,
			'alt'=>HtmlHelper::encode($data['title']),
		)), FriendlyLink::getSpecialLink(array(
			'id'=>$data['id']
		)), array(
			'encode'=>false,
			'title'=>HtmlHelper::encode($data['title']),
		))?>
	</div>
	<div class="sp_name fr">
		<h2>
			<?php echo HtmlHelper::link($data['title'], FriendlyLink::getSpecialLink(array(
				'id'=>$data['id']
			)), array(
				'target'=>'_blank',
			))?>
		</h2>
		<p><?php echo HtmlHelper::encode($data['abstract'])?></p>
		<div class="sp_bth">
			<?php echo HtmlHelper::link('详细', FriendlyLink::getTravelLink(array(
				'id'=>$data['id'],
			)))?>
		</div>
	</div>
</li>