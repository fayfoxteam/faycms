<?php
use fay\helpers\Html;
use fay\models\File;
use ncp\helpers\FriendlyLink;
?>
<li>
	<div class="sp_img flrwyj">
		<?php echo Html::link(Html::img($data['thumbnail'], File::PIC_RESIZE, array(
			'dw'=>400,
			'dh'=>284,
			'alt'=>Html::encode($data['title']),
		)), FriendlyLink::getSpecialLink(array(
			'id'=>$data['id']
		)), array(
			'encode'=>false,
			'title'=>Html::encode($data['title']),
		))?>
	</div>
	<div class="sp_name fr">
		<h2>
			<?php echo Html::link($data['title'], FriendlyLink::getSpecialLink(array(
				'id'=>$data['id']
			)), array(
				'target'=>'_blank',
			))?>
		</h2>
		<p><?php echo Html::encode($data['abstract'])?></p>
		<div class="sp_bth">
			<?php echo Html::link('详细', FriendlyLink::getTravelLink(array(
				'id'=>$data['id'],
			)))?>
		</div>
	</div>
</li>